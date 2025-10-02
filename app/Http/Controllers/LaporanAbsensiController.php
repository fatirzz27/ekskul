<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ekskul;
use App\Models\LaporanAbsensi;
use Illuminate\Support\Facades\Auth;

class LaporanAbsensiController extends Controller
{
    public function index(Ekskul $ekskul, Request $request)
    {
        // Pastikan hanya pembina ekskul terkait yang bisa akses
        abort_unless($ekskul->pembina->contains(Auth::id()), 403);

        // Filter berdasarkan tanggal jika ada request
        $tanggal = $request->input('tanggal');

        $query = LaporanAbsensi::where('ekskul_id', $ekskul->id);

        if ($tanggal) {
            $query->whereDate('tanggal', $tanggal);
        }

        $laporan = $query->orderBy('tanggal', 'desc')->paginate(10);

        return view('pembina.laporan.index', compact('ekskul', 'laporan', 'tanggal'));
    }
}
