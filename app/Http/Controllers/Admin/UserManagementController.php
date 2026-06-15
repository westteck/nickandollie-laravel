<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    /**
     * Display the user management page.
     */
    public function index(): View
    {
        return view('admin.users');
    }

    /**
     * List all users (JSON).
     */
    public function list(): JsonResponse
    {
        $users = DB::table('users')
            ->select(
                'users.id',
                'users.guest_name',
                'users.first_name',
                'users.last_name',
                'users.username',
                'users.email',
                'users.user_type',
                'users.connection',
                'users.core_group',
                'users.specific_relationship',
                'users.created_at',
                'users.updated_at',
                DB::raw('COALESCE(conn.label, users.connection) as connection_label'),
                DB::raw('COALESCE(cg.label, users.core_group) as core_group_label'),
                'ab.address',
                'ab.city',
                'ab.state',
                'ab.zip',
                'ab.phone as ab_phone',
                'ab.mobile',
                'ab.email as ab_email',
                'ab.show_in_phonebook'
            )
            ->leftJoin('lookup_options as conn', function ($join) {
                $join->on('users.connection_id', '=', 'conn.id')
                     ->where('conn.category', '=', 'connection');
            })
            ->leftJoin('lookup_options as cg', function ($join) {
                $join->on('users.core_group_id', '=', 'cg.id')
                     ->where('cg.category', '=', 'core_group');
            })
            ->leftJoin('address_book as ab', 'ab.user_id', '=', 'users.id')
            ->orderBy('users.created_at', 'desc')
            ->get();

        return response()->json(['success' => true, 'users' => $users]);
    }

    /**
     * Create a new user (JSON).
     */
    public function store(Request $request): JsonResponse
    {
        $input = $request->validate([
            'firstname' => ['required', 'string', 'max:100'],
            'lastname' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:30', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'user_type' => ['nullable', 'in:user,admin'],
            'connection' => ['nullable', 'string'],
            'core_group' => ['nullable', 'string'],
            'specific_relationship' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:50'],
            'zip' => ['nullable', 'string', 'max:20'],
            'pb_email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'mobile' => ['nullable', 'string', 'max:30'],
        ]);

        $guestName = trim($input['firstname'] . ' ' . $input['lastname']);

        $userId = DB::table('users')->insertGetId([
            'guest_name' => $guestName,
            'first_name' => $input['firstname'],
            'last_name' => $input['lastname'],
            'username' => $input['username'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'user_type' => $input['user_type'] ?? 'user',
            'connection' => $input['connection'] ?: null,
            'core_group' => $input['core_group'] ?: null,
            'specific_relationship' => $input['specific_relationship'] ?: null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create address_book entry
        DB::table('address_book')->insert([
            'user_id' => $userId,
            'entry_name' => $guestName,
            'first_name' => $input['firstname'],
            'address' => $input['address'] ?? null,
            'city' => $input['city'] ?? null,
            'state' => $input['state'] ?? null,
            'zip' => $input['zip'] ?? null,
            'email' => $input['pb_email'] ?? null,
            'phone' => $input['phone'] ?? null,
            'mobile' => $input['mobile'] ?? null,
            'show_in_phonebook' => 1,
            'created_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'User created', 'id' => $userId]);
    }

    /**
     * Update a user (JSON).
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $user = DB::table('users')->where('id', $id)->first();
        if (!$user) {
            return response()->json(['success' => false, 'error' => 'User not found'], 404);
        }

        $input = $request->all();
        $allowed = ['guest_name', 'username', 'email', 'user_type', 'connection', 'core_group', 'specific_relationship', 'first_name', 'last_name'];
        $sets = [];
        $params = [];

        foreach ($allowed as $field) {
            if (isset($input[$field])) {
                $sets[] = "`$field` = :$field";
                $params[$field] = $input[$field] === '' ? null : $input[$field];
            }
        }

        // Password change
        if (!empty($input['password'])) {
            $sets[] = '`password` = :password';
            $params['password'] = Hash::make($input['password']);
        }

        if (empty($sets)) {
            return response()->json(['success' => false, 'error' => 'No fields to update'], 400);
        }

        // Duplicate guard
        if (!empty($input['username'])) {
            $exists = DB::table('users')->where('username', $input['username'])->where('id', '!=', $id)->exists();
            if ($exists) {
                return response()->json(['success' => false, 'error' => 'Username already taken'], 400);
            }
        }
        if (!empty($input['email'])) {
            $exists = DB::table('users')->where('email', $input['email'])->where('id', '!=', $id)->exists();
            if ($exists) {
                return response()->json(['success' => false, 'error' => 'Email already registered'], 400);
            }
        }

        $params['id'] = $id;
        $sql = 'UPDATE users SET ' . implode(', ', $sets) . ', updated_at = NOW() WHERE id = :id';
        DB::update($sql, $params);

        // Update address_book fields
        $abFields = ['address', 'city', 'state', 'zip', 'phone', 'mobile'];
        $abSets = [];
        $abParams = [];

        foreach ($abFields as $f) {
            if (isset($input[$f])) {
                $abSets[] = "$f = :ab_$f";
                $abParams[":ab_$f"] = trim($input[$f]);
            }
        }
        if (!empty($input['guest_name'])) {
            $abSets[] = "entry_name = :ab_entry_name";
            $abParams[':ab_entry_name'] = trim($input['guest_name']);
        }
        if (!empty($input['first_name'])) {
            $abSets[] = "first_name = :ab_first_name";
            $abParams[':ab_first_name'] = trim($input['first_name']);
        }
        if (isset($input['pb_email'])) {
            $abSets[] = "email = :ab_email";
            $abParams[':ab_email'] = trim($input['pb_email']);
        }

        if (!empty($abSets)) {
            $abExists = DB::table('address_book')->where('user_id', $id)->exists();
            $abParams[':uid'] = $id;
            if ($abExists) {
                $abSql = 'UPDATE address_book SET ' . implode(', ', $abSets) . ', updated_at = NOW() WHERE user_id = :uid';
                DB::update($abSql, $abParams);
            } else {
                $cols = array_merge(array_keys($abParams), ['user_id', 'show_in_phonebook']);
                $placeholders = array_map(function ($k) { return ":$k"; }, array_keys($abParams));
                $placeholders[] = ':uid';
                $placeholders[] = ':show';
                $abSql = 'INSERT INTO address_book (' . implode(', ', array_keys($abParams)) . ', user_id, show_in_phonebook) VALUES (' . implode(', ', $placeholders) . ')';
                $abParams[':show'] = 1;
                DB::insert($abSql, $abParams);
            }
        }

        return response()->json(['success' => true, 'message' => 'User updated']);
    }

    /**
     * Delete a user (JSON).
     */
    public function destroy(int $id): JsonResponse
    {
        if ($id === auth()->id()) {
            return response()->json(['success' => false, 'error' => 'Cannot delete yourself'], 400);
        }

        DB::table('address_book')->where('user_id', $id)->delete();
        DB::table('favorites')->where('user_id', $id)->delete();
        DB::table('comments')->where('user_id', $id)->delete();
        DB::table('ratings')->where('user_id', $id)->delete();
        DB::table('votes')->where('user_id', $id)->delete();
        DB::table('contest_entries')->where('submitted_by', $id)->delete();
        DB::table('photos')->where('uploader_id', $id)->delete();
        DB::table('users')->where('id', $id)->delete();

        return response()->json(['success' => true, 'message' => 'User deleted']);
    }
}
