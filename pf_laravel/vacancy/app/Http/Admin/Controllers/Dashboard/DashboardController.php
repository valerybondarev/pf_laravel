<?php


namespace App\Http\Admin\Controllers\Dashboard;


use App\Http\Admin\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.dashboard.index');
    }
}
