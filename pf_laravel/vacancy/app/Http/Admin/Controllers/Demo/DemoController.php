<?php

namespace App\Http\Admin\Controllers\Demo;

use App\Http\Admin\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class DemoController extends Controller
{
    public function index(): Factory|View|Application
    {
        return view('admin.demo.index');
    }
}
