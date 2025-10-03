<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ekskul;
use App\Models\LaporanAbsensi;
use App\Exports\AbsensiExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        try {
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

            // Log untuk debugging di production
            Log::info('Export attempt', [
                'ekskul_id' => $ekskul->id,
                'tanggal' => $tanggal,
                'format' => $format,
                'data_count' => $count,
                'temp_dir' => sys_get_temp_dir(),
                'memory_limit' => ini_get('memory_limit'),
                'max_execution_time' => ini_get('max_execution_time')
            ]);

            // Increase memory limit for production
            ini_set('memory_limit', '256M');
            ini_set('max_execution_time', 300); // 5 minutes

            $export = new AbsensiExport($ekskul->id, $tanggal);
            
            // Generate filename - sanitize untuk production
            $ekskulName = str_replace([' ', '/', '\\', ':', '*', '?', '"', '<', '>', '|'], '_', $ekskul->nama_ekskul);
            $filename = 'laporan_absensi_' . $ekskulName . '_' . $tanggal;
            
            // Clear any output buffer
            if (ob_get_level()) {
                ob_end_clean();
            }
            
            if ($format === 'csv') {
                Log::info('Exporting CSV', ['filename' => $filename . '.csv']);
                return Excel::download($export, $filename . '.csv', \Maatwebsite\Excel\Excel::CSV, [
                    'Content-Type' => 'text/csv',
                ]);
            }
            
            // Default Excel format (.xlsx) - menggunakan maatwebsite/excel yang benar
            Log::info('Exporting Excel', ['filename' => $filename . '.xlsx']);
            return Excel::download($export, $filename . '.xlsx', \Maatwebsite\Excel\Excel::XLSX, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]);

        } catch (\Exception $e) {
            Log::error('Export failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ekskul_id' => $ekskul->id ?? null,
                'tanggal' => $tanggal ?? null,
                'format' => $format ?? null
            ]);

            return back()->withErrors(['export' => 'Terjadi kesalahan saat export. Silakan coba lagi. Error: ' . $e->getMessage()]);
        }
    }
}
