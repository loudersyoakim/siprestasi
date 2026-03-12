<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class DashboardController extends Controller
{

    public function adminDashboard()
    {
        return view('admin.dashboard');
    }

    public function wdDashboard()
    {
        return view('wd.dashboard');
    }

    public function kajurDashboard()
    {
        return view('kajur.dashboard');
    }

    public function gpmDashboard()
    {
        return view('gpm.dashboard');
    }

    public function mahasiswaDashboard()
    {
        return view('mahasiswa.dashboard');
    }
}
