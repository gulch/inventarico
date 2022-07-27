<?php

namespace App\Http\Controllers;

use App\Models\Instance;
use App\Models\Thing;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'active_instances_sum' => Instance::where('is_archived', 0)->sum('price'),
            'active_things_count' => Thing::where('is_archived', 0)->count(),
            'archived_things_count' => Thing::where('is_archived', 1)->count(),
            'active_instances_count' => Instance::where('is_archived', 0)->count(),
            'archived_instances_count' => Instance::where('is_archived', 1)->count(),
        ];

        return view('dashboard', $data);
    }
}
