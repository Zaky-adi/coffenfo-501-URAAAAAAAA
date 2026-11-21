<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        return view('admin.dashboard.index');
    }
    public function kelola()
    {
        return view('admin.dashboard.kelola');
    }
    public function kelolabeans()
    {
        return view('admin.dashboard.kelolabeans');
    }

    public function review()
    {
        return view('admin.dashboard.review');
    }
    public function laporan()
    {
        return view('admin.dashboard.laporan');
    }

    public function pengaturan()
    {
        return view('admin.dashboard.pengaturan');
    }
}