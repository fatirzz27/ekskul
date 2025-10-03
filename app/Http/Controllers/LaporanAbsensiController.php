<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ekskul;
use App\Models\LaporanAbsensi;
use App\Exports\AbsensiExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LaporanAbsensiController extends Controller
{
    public function index(Ekskul $ekskul, Request $request)
    {
        // Pastikan hanya pembina ekskul terkait yang bisa akses
        abort_unless($ekskul->pembina->contains(Auth::id()), 403);

        // Filter berdasarkan tanggal jika ada request
        $tanggal = $request->input('tanggal');
        $page = $request->input('page', 1);
        $perPage = 3; // 3 laporan (tanggal) per halaman

        $query = LaporanAbsensi::where('ekskul_id', $ekskul->id);

        if ($tanggal) {
            $query->whereDate('tanggal', $tanggal);
            // Jika ada filter tanggal, tidak perlu pagination
            $laporan = $query->orderBy('tanggal', 'desc')->get();
            $pagination = null;
        } else {
            // Ambil tanggal unik terlebih dahulu
            $uniqueDates = LaporanAbsensi::where('ekskul_id', $ekskul->id)
                ->selectRaw('DATE(tanggal) as date')
                ->distinct()
                ->orderBy('date', 'desc')
                ->get()
                ->pluck('date');

            // Pagination manual untuk tanggal
            $total = $uniqueDates->count();
            $offset = ($page - 1) * $perPage;
            $paginatedDates = $uniqueDates->slice($offset, $perPage);

            // Ambil data absensi untuk tanggal-tanggal yang dipaginasi
            $laporan = LaporanAbsensi::where('ekskul_id', $ekskul->id)
                ->whereIn(DB::raw('DATE(tanggal)'), $paginatedDates)
                ->orderBy('tanggal', 'desc')
                ->get();

            // Buat pagination info
            $pagination = [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'last_page' => max(1, ceil($total / $perPage)),
                'from' => $total > 0 ? $offset + 1 : 0,
                'to' => min($offset + $perPage, $total),
            ];
        }

        return view('pembina.laporan.index', compact('ekskul', 'laporan', 'tanggal', 'pagination'));
    }

    public function export(Ekskul $ekskul, Request $request)
    {
        // Pastikan hanya pembina ekskul terkait yang bisa akses
        abort_unless($ekskul->pembina->contains(Auth::id()), 403);

        $tanggal = $request->input('tanggal');
        $format = $request->input('format', 'excel'); // default excel
        
        if (!$tanggal) {
            return back()->withErrors(['tanggal' => 'Tanggal harus dipilih untuk export.']);
        }

        // Cek apakah ada data untuk tanggal tersebut
        $count = LaporanAbsensi::where('ekskul_id', $ekskul->id)
            ->whereDate('tanggal', $tanggal)
            ->count();
            
        if ($count == 0) {
            return back()->withErrors(['tanggal' => 'Tidak ada data absensi untuk tanggal yang dipilih.']);
        }

        $export = new AbsensiExport($ekskul->id, $tanggal);
        
        if ($format === 'csv') {
            return $export->generateCsv();
        }
        
        return $export->generateExcel(); // Default to Excel format
    }
}
