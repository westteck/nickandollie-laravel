<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class ContestController extends Controller
{
    public function __invoke(): View
    {
        return view('contest');
    }
}
