<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ContestController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.contests');
    }
}
