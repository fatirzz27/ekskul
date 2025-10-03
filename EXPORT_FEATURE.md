# Fitur Export Laporan Absensi

## Deskripsi
Fitur ini memungkinkan pembina ekstrakurikuler untuk mengexport laporan absensi ke dalam format Excel (.xls) atau CSV (.csv).

## Cara Penggunaan

### 1. Export dari Filter Tanggal
1. Buka halaman laporan absensi ekstrakurikuler
2. Pilih tanggal pada filter tanggal
3. Klik tombol "Search" untuk menampilkan data
4. Klik tombol dropdown "Export" 
5. Pilih format yang diinginkan:
   - **Excel (.xls)**: Format tabel dengan styling dan ringkasan
   - **CSV (.csv)**: Format sederhana yang kompatibel dengan semua aplikasi

### 2. Export dari Accordion Row
1. Setelah data ditampilkan per tanggal
2. Pada setiap baris tanggal, terdapat tombol dropdown export di sebelah kanan
3. Klik dropdown tersebut dan pilih format export

## Format Export

### Excel (.xls)
- Header dengan informasi ekstrakurikuler dan tanggal
- Tabel data dengan styling warna untuk status
- Ringkasan jumlah hadir, izin, dan alfa
- Format yang rapi untuk presentasi

### CSV (.csv)
- Format sederhana dengan encoding UTF-8
- Mudah dibuka di Excel, Google Sheets, atau aplikasi lain
- Header dan data lengkap
- Cocok untuk pemrosesan data lebih lanjut

## Keamanan
- Hanya pembina ekstrakurikuler yang bisa mengakses export
- Validasi data sebelum export
- Pesan error jika tidak ada data untuk tanggal yang dipilih

## File yang Dibuat/Dimodifikasi
1. `app/Exports/AbsensiExport.php` - Class untuk generate export
2. `app/Http/Controllers/LaporanAbsensiController.php` - Method export
3. `resources/views/pembina/laporan/index.blade.php` - UI export
4. `routes/web.php` - Route export