<?php

namespace App\Exports;

use App\Models\LaporanAbsensi;
use Carbon\Carbon;

class AbsensiExport
{
    protected $ekskulId;
    protected $tanggal;

    public function __construct($ekskulId, $tanggal)
    {
        $this->ekskulId = $ekskulId;
        $this->tanggal = $tanggal;
    }

    public function generateCsv()
    {
        $data = LaporanAbsensi::where('ekskul_id', $this->ekskulId)
            ->whereDate('tanggal', $this->tanggal)
            ->with(['user', 'ekskul'])
            ->orderBy('user_id')
            ->get();

        $ekskul = $data->first()->ekskul ?? null;
        $ekskulName = $ekskul ? $ekskul->nama_ekskul : 'Unknown';
        
        $filename = 'laporan_absensi_' . str_replace(' ', '_', $ekskulName) . '_' . $this->tanggal . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ];

        $callback = function() use ($data, $ekskulName) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8 to ensure proper encoding in Excel
            fwrite($file, "\xEF\xBB\xBF");
            
            // Add title and info
            fputcsv($file, ['LAPORAN ABSENSI EKSTRAKURIKULER']);
            fputcsv($file, ['Ekstrakurikuler:', $ekskulName]);
            fputcsv($file, ['Tanggal:', Carbon::parse($this->tanggal)->format('d F Y')]);
            fputcsv($file, ['Total Siswa:', count($data)]);
            fputcsv($file, []); // Empty row
            
            // Header row
            fputcsv($file, [
                'No',
                'Nama Siswa',
                'Status Kehadiran',
                'Keterangan'
            ]);

            // Data rows
            $no = 1;
            $hadir = 0;
            $izin = 0;
            $alfa = 0;
            
            foreach ($data as $row) {
                $status = ucfirst($row->status);
                
                switch($row->status) {
                    case 'hadir': $hadir++; break;
                    case 'izin': $izin++; break;
                    case 'alfa': $alfa++; break;
                }
                
                fputcsv($file, [
                    $no++,
                    $row->user->name,
                    $status,
                    $row->keterangan ?? '-'
                ]);
            }
            
            // Summary
            fputcsv($file, []); // Empty row
            fputcsv($file, ['RINGKASAN:']);
            fputcsv($file, ['Hadir:', $hadir]);
            fputcsv($file, ['Izin:', $izin]);
            fputcsv($file, ['Alfa:', $alfa]);
            fputcsv($file, ['Total:', count($data)]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function generateExcel()
    {
        // Get data untuk tanggal tertentu
        $data = LaporanAbsensi::where('ekskul_id', $this->ekskulId)
            ->whereDate('tanggal', $this->tanggal)
            ->with(['user', 'ekskul'])
            ->orderBy('user_id')
            ->get();

        // Jika tidak ada data, buat file kosong dengan pesan
        if ($data->isEmpty()) {
            $ekskul = \App\Models\Ekskul::find($this->ekskulId);
            $ekskulName = $ekskul ? $ekskul->nama_ekskul : 'Unknown';
            
            $filename = 'laporan_absensi_' . str_replace(' ', '_', $ekskulName) . '_' . $this->tanggal . '.xls';
            
            $headers = [
                'Content-Type' => 'application/vnd.ms-excel',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ];

            $html = '<html><head><meta charset="UTF-8"></head><body>';
            $html .= '<table border="1" cellpadding="5" cellspacing="0">';
            $html .= '<tr><td colspan="4" style="text-align:center; font-weight:bold; font-size:16px;">LAPORAN ABSENSI EKSTRAKURIKULER</td></tr>';
            $html .= '<tr><td><strong>Ekstrakurikuler:</strong></td><td colspan="3">' . htmlspecialchars($ekskulName) . '</td></tr>';
            $html .= '<tr><td><strong>Tanggal:</strong></td><td colspan="3">' . Carbon::parse($this->tanggal)->format('d F Y') . '</td></tr>';
            $html .= '<tr><td colspan="4" style="text-align:center; color:red;">Tidak ada data absensi untuk tanggal ini</td></tr>';
            $html .= '</table></body></html>';

            return response($html, 200, $headers);
        }

        $ekskul = $data->first()->ekskul;
        $ekskulName = $ekskul->nama_ekskul;
        
        $filename = 'laporan_absensi_' . str_replace(' ', '_', $ekskulName) . '_' . $this->tanggal . '.xls';
        
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ];

        $html = $this->generateHtmlTable($data, $ekskulName);

        return response($html, 200, $headers);
    }

    private function generateHtmlTable($data, $ekskulName)
    {
        $hadir = $data->where('status', 'hadir')->count();
        $izin = $data->where('status', 'izin')->count();
        $alfa = $data->where('status', 'alfa')->count();
        
        $html = '<html><head><meta charset="UTF-8"></head><body>';
        $html .= '<table border="1" cellpadding="5" cellspacing="0">';
        
        // Title and info
        $html .= '<tr><td colspan="4" style="text-align:center; font-weight:bold; font-size:16px;">LAPORAN ABSENSI EKSTRAKURIKULER</td></tr>';
        $html .= '<tr><td><strong>Ekstrakurikuler:</strong></td><td colspan="3">' . htmlspecialchars($ekskulName) . '</td></tr>';
        $html .= '<tr><td><strong>Tanggal:</strong></td><td colspan="3">' . Carbon::parse($this->tanggal)->format('d F Y') . '</td></tr>';
        $html .= '<tr><td><strong>Total Siswa:</strong></td><td colspan="3">' . count($data) . '</td></tr>';
        $html .= '<tr><td colspan="4"></td></tr>'; // Empty row
        
        // Header
        $html .= '<tr style="background-color: #4472C4; color: white; font-weight: bold;">';
        $html .= '<td>No</td><td>Nama Siswa</td><td>Status Kehadiran</td><td>Keterangan</td>';
        $html .= '</tr>';
        
        // Data rows
        $no = 1;
        foreach ($data as $row) {
            $statusColor = '';
            switch($row->status) {
                case 'hadir': $statusColor = 'color: green; font-weight: bold;'; break;
                case 'izin': $statusColor = 'color: orange; font-weight: bold;'; break;
                case 'alfa': $statusColor = 'color: red; font-weight: bold;'; break;
            }
            
            $html .= '<tr>';
            $html .= '<td>' . $no++ . '</td>';
            $html .= '<td>' . htmlspecialchars($row->user->name) . '</td>';
            $html .= '<td style="' . $statusColor . '">' . ucfirst($row->status) . '</td>';
            $html .= '<td>' . htmlspecialchars($row->keterangan ?? '-') . '</td>';
            $html .= '</tr>';
        }
        
        // Summary
        $html .= '<tr><td colspan="4"></td></tr>'; // Empty row
        $html .= '<tr><td colspan="4" style="font-weight:bold;">RINGKASAN:</td></tr>';
        $html .= '<tr><td><strong>Hadir:</strong></td><td style="color: green; font-weight: bold;">' . $hadir . '</td><td></td><td></td></tr>';
        $html .= '<tr><td><strong>Izin:</strong></td><td style="color: orange; font-weight: bold;">' . $izin . '</td><td></td><td></td></tr>';
        $html .= '<tr><td><strong>Alfa:</strong></td><td style="color: red; font-weight: bold;">' . $alfa . '</td><td></td><td></td></tr>';
        $html .= '<tr><td><strong>Total:</strong></td><td style="font-weight: bold;">' . count($data) . '</td><td></td><td></td></tr>';
        
        $html .= '</table></body></html>';
        
        return $html;
    }
}