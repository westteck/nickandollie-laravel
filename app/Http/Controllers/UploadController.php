<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class UploadController extends Controller
{
    public function __invoke(): View
    {
        return view('upload');
    }
}
