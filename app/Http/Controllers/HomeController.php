<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use function auth;
use function redirect;
use function view;

final class HomeController extends Controller
{
    public function index()
    {
        if (auth()->check()) {
            return redirect('dashboard');
        }

        return view('home');
    }
}
