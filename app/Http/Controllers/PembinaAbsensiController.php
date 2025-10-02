<?php

namespace App\Http\Controllers;

use App\Models\Ekskul;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembinaAbsensiController extends Controller
{
    public function index(Ekskul $ekskul)
    {
        // cek apakah user pembina ekskul
        abort_unless($ekskul->pembina->contains(Auth::id()), 403, 'Unauthorized');

        $anggota = $ekskul->anggota; // semua siswa di ekskul
        return view('pembina.absensi.index', compact('ekskul', 'anggota'));
    }

    public function store(Request $request, Ekskul $ekskul)
    {
        $data = $request->validate([
            'tanggal' => 'required|date',
            'absensi' => 'array',
            'keterangan' => 'array',
        ]);

        foreach ($ekskul->anggota as $user) {
            $status = $data['absensi'][$user->id] ?? null;
            $keterangan = $data['keterangan'][$user->id] ?? null;

            // Kalau ada keterangan, otomatis status jadi izin
            if (!empty($keterangan)) {
                $status = 'izin';
            }

            Absensi::create([
                'user_id'   => $user->id,  // ✅ ganti siswa_id jadi user_id
                'ekskul_id' => $ekskul->id,
                'tanggal'   => $data['tanggal'],
                'status'    => $status,
                'keterangan'=> $keterangan,
            ]);
        }

        return redirect()->route('pembina.absensi.index', $ekskul)
                         ->with('success', 'Absensi berhasil disimpan.');
    }
}
