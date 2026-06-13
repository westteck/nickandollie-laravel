<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PhonebookController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.phonebook');
    }
}
