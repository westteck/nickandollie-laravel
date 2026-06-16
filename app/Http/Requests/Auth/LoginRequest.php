<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'login' => ['nullable', 'string'],
            'email' => ['nullable', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * The login field to use for authentication.
     *
     * Breeze's stock form posts `email`. The home page form posts `login`
     * (which can be an email or a username). This helper returns whichever
     * is present, with `login` winning if both are sent.
     */
    public function loginValue(): string
    {
        $login = trim((string) $this->input('login'));
        if ($login !== '') {
            return $login;
        }
        return trim((string) $this->input('email'));
    }

    /**
     * Validation entry — at least one of login/email must be non-empty.
     *
     * @throws ValidationException
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            if ($this->loginValue() === '') {
                $v->errors()->add('login', trans('auth.failed'));
            }
        });
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * Accepts an email or username in the `login` field. The legacy schema
     * has a `username` column on users in addition to `email`, so the home
     * page form lets guests type either.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $login = $this->loginValue();
        $password = (string) $this->input('password');
        $remember = $this->boolean('remember');

        if ($login === '') {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'login' => trans('auth.failed'),
            ]);
        }

        // Resolve the email from the supplied login (email or username)
        $email = filter_var($login, FILTER_VALIDATE_EMAIL)
            ? $login
            : \App\Models\User::where('username', $login)->value('email');

        if (!$email) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'login' => trans('auth.failed'),
            ]);
        }

        if (! Auth::attempt(['email' => $email, 'password' => $password], $remember)) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'login' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'login' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('login')).'|'.$this->ip());
    }
}
