<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Exports\AbsensiExport;
use App\Models\LaporanAbsensi;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class TestExport extends Command
{
    protected $signature = 'excel:test {ekskul_id} {tanggal}';
    protected $description = 'Test export functionality for debugging';

    public function handle()
    {
        $ekskulId = $this->argument('ekskul_id');
        $tanggal = $this->argument('tanggal');

        $this->info("Testing export for Ekskul ID: {$ekskulId}, Tanggal: {$tanggal}");

        // Check system info
        $this->info("PHP Version: " . PHP_VERSION);
        $this->info("Memory Limit: " . ini_get('memory_limit'));
        $this->info("Max Execution Time: " . ini_get('max_execution_time'));
        $this->info("Temp Directory: " . sys_get_temp_dir());
        $this->info("Storage Path: " . storage_path());

        // Check data
        $count = LaporanAbsensi::where('ekskul_id', $ekskulId)
            ->whereDate('tanggal', $tanggal)
            ->count();

        $this->info("Data count: {$count}");

        if ($count == 0) {
            $this->error("No data found for the specified criteria");
            return;
        }

        try {
            $export = new AbsensiExport($ekskulId, $tanggal);
            $filename = 'test_export_' . time() . '.xlsx';
            
            $this->info("Creating export...");
            
            // Test export to storage instead of download
            Excel::store($export, $filename, 'local');
            
            $filePath = storage_path('app/' . $filename);
            
            if (file_exists($filePath)) {
                $fileSize = filesize($filePath);
                $this->info("Export successful!");
                $this->info("File created: {$filePath}");
                $this->info("File size: {$fileSize} bytes");
                
                // Clean up
                unlink($filePath);
                $this->info("Test file deleted");
            } else {
                $this->error("Export failed - file not created");
            }

        } catch (\Exception $e) {
            $this->error("Export failed with error: " . $e->getMessage());
            $this->error("Stack trace: " . $e->getTraceAsString());
        }
    }
}