<?php

namespace App\Http\Controllers;

use App\Models\Ekskul;
use Illuminate\Support\Facades\Auth;

class PembinaAnggotaController extends Controller
{
    public function index(Ekskul $ekskul)
    {
        $user = Auth::user();
    
        abort_unless($ekskul->pembina->contains(Auth::id()), 403, 'Unauthorized');
    
        // load profile juga
        $anggota = $ekskul->anggota()->with('profile')->paginate(1);
    
        return view('pembina.anggota.index', compact('ekskul', 'anggota'));
    }
    
    

    public function destroy(Ekskul $ekskul, $userId)
    {
        $user = Auth::user();

        abort_unless($ekskul->pembina->contains(Auth::id()), 403, 'Unauthorized');

        $ekskul->anggota()->detach($userId);

        return redirect()->route('pembina.anggota.index', $ekskul)
            ->with('success', 'Anggota berhasil dihapus dari ekskul.');
    }
}
