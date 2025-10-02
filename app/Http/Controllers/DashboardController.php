<?php

namespace App\Http\Controllers;

use App\Models\Ekskul;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $ekskuls = Ekskul::latest()->take(6)->get(); // Ambil 6 ekskul terbaru untuk dashboard

        return view('dashboard', compact('user', 'ekskuls'));
    }
}
