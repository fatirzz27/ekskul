<?php

namespace App\Exports;

use App\Models\LaporanAbsensi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class AbsensiExport implements 
    FromCollection, 
    WithHeadings, 
    WithMapping, 
    WithTitle,
    WithStyles,
    WithColumnWidths,
    WithCustomStartCell,
    WithEvents
{
    protected $ekskulId;
    protected $tanggal;
    protected $ekskulName;
    protected $data;

    public function __construct($ekskulId, $tanggal)
    {
        $this->ekskulId = $ekskulId;
        $this->tanggal = $tanggal;
        
        try {
            // Load data
            $this->data = LaporanAbsensi::where('ekskul_id', $ekskulId)
                ->whereDate('tanggal', $tanggal)
                ->with(['user', 'ekskul'])
                ->orderBy('user_id')
                ->get();
                
            $this->ekskulName = $this->data->first()?->ekskul?->nama_ekskul ?? 'Unknown';
            
            // Log untuk debugging
            Log::info('AbsensiExport initialized', [
                'ekskul_id' => $ekskulId,
                'tanggal' => $tanggal,
                'data_count' => $this->data->count(),
                'ekskul_name' => $this->ekskulName
            ]);
            
        } catch (\Exception $e) {
            Log::error('AbsensiExport initialization failed', [
                'error' => $e->getMessage(),
                'ekskul_id' => $ekskulId,
                'tanggal' => $tanggal
            ]);
            
            // Fallback data kosong
            $this->data = collect();
            $this->ekskulName = 'Unknown';
        }
    }

    public function collection()
    {
        return $this->data;
    }

    public function map($row): array
    {
        static $no = 1;
        
        return [
            $no++,
            $row->user->name,
            ucfirst($row->status),
            $row->keterangan ?? '-'
        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Siswa',
            'Status Kehadiran',
            'Keterangan'
        ];
    }

    public function title(): string
    {
        return 'Laporan Absensi';
    }

    public function startCell(): string
    {
        return 'A6'; // Start data dari baris 6
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 25,  // Nama Siswa
            'C' => 15,  // Status
            'D' => 30,  // Keterangan
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style untuk header data
            6 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '4472C4']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Title dan info di bagian atas
                $sheet->setCellValue('A1', 'LAPORAN ABSENSI EKSTRAKURIKULER');
                $sheet->setCellValue('A2', 'Ekstrakurikuler: ' . $this->ekskulName);
                $sheet->setCellValue('A3', 'Tanggal: ' . Carbon::parse($this->tanggal)->format('d F Y'));
                $sheet->setCellValue('A4', 'Total Siswa: ' . $this->data->count());

                // Style untuk title
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                // Merge title
                $sheet->mergeCells('A1:D1');
                
                // Style untuk info
                $sheet->getStyle('A2:A4')->applyFromArray([
                    'font' => ['bold' => true],
                ]);

                // Hitung ringkasan
                $hadir = $this->data->where('status', 'hadir')->count();
                $izin = $this->data->where('status', 'izin')->count();
                $alfa = $this->data->where('status', 'alfa')->count();

                // Tambahkan ringkasan di bawah data
                $lastRow = 6 + $this->data->count() + 2; // 6 (start) + data + 2 (spacing)
                
                $sheet->setCellValue('A' . $lastRow, 'RINGKASAN:');
                $sheet->setCellValue('A' . ($lastRow + 1), 'Hadir:');
                $sheet->setCellValue('B' . ($lastRow + 1), $hadir);
                $sheet->setCellValue('A' . ($lastRow + 2), 'Izin:');
                $sheet->setCellValue('B' . ($lastRow + 2), $izin);
                $sheet->setCellValue('A' . ($lastRow + 3), 'Alfa:');
                $sheet->setCellValue('B' . ($lastRow + 3), $alfa);
                $sheet->setCellValue('A' . ($lastRow + 4), 'Total:');
                $sheet->setCellValue('B' . ($lastRow + 4), $this->data->count());

                // Style untuk ringkasan
                $sheet->getStyle('A' . $lastRow . ':B' . ($lastRow + 4))->applyFromArray([
                    'font' => ['bold' => true],
                ]);

                // Warna untuk status dalam data
                $dataStartRow = 7; // Row setelah header
                foreach ($this->data as $index => $row) {
                    $currentRow = $dataStartRow + $index;
                    $statusCell = 'C' . $currentRow;
                    
                    switch($row->status) {
                        case 'hadir':
                            $sheet->getStyle($statusCell)->applyFromArray([
                                'font' => ['color' => ['rgb' => '008000'], 'bold' => true],
                            ]);
                            break;
                        case 'izin':
                            $sheet->getStyle($statusCell)->applyFromArray([
                                'font' => ['color' => ['rgb' => 'FF8C00'], 'bold' => true],
                            ]);
                            break;
                        case 'alfa':
                            $sheet->getStyle($statusCell)->applyFromArray([
                                'font' => ['color' => ['rgb' => 'FF0000'], 'bold' => true],
                            ]);
                            break;
                    }
                }

                // Border untuk tabel data
                $dataRange = 'A6:D' . (6 + $this->data->count());
                $sheet->getStyle($dataRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);
            },
        ];
    }

    // Backup methods untuk compatibility dengan kode lama
    public function generateCsv()
    {
        $data = $this->data;
        $ekskulName = $this->ekskulName;
        
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
        // Fallback untuk compatibility - redirect ke method baru
        return $this->generateModernExcel();
    }

    private function generateModernExcel()
    {
        $ekskulName = str_replace(' ', '_', $this->ekskulName);
        $filename = 'laporan_absensi_' . $ekskulName . '_' . $this->tanggal . '.xlsx';
        
        return \Maatwebsite\Excel\Facades\Excel::download($this, $filename);
    }
}