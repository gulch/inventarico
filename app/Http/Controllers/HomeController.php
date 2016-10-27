<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        if (!auth()->guest()) {
            return redirect('dashboard');
        }
        return view('home');
    }
}
