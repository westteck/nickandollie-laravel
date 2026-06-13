<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PhonebookController extends Controller
{
    public function __invoke(): View
    {
        return view('phonebook');
    }
}
