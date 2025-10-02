<?php

namespace App\Http\Controllers;

use App\Models\Ekskul;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EkskulController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index','show']);
    }

    private function authorizeEditor()
    {
        // Batasi create/update/delete untuk admin & pembina
        if (!in_array(Auth::user()->role, ['admin','pembina'])) {
            abort(403, 'Unauthorized');
        }
    }

    public function index()
    {
        $ekskuls = Ekskul::with('anggota')->latest()->paginate(9);
        return view('ekskul.index', compact('ekskuls'));
    }

    public function create()
    {
        $this->authorizeEditor();
        return view('ekskul.create');
    }

    public function store(Request $request)
    {
        $this->authorizeEditor();

        $validated = $request->validate([
            'nama_ekskul' => ['required','string','max:100'],
            'deskripsi'   => ['nullable','string'],
            'foto'        => ['nullable','image','mimes:jpg,jpeg,png','max:2048'],
        ]);

        $filename = 'default.jpg';
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = uniqid().'_'.time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('images/ekskul'), $filename);
        }

        Ekskul::create([
            'nama_ekskul' => $validated['nama_ekskul'],
            'deskripsi'   => $validated['deskripsi'] ?? null,
            'foto'        => $filename,
        ]);

        return redirect()->route('ekskul.index')->with('success','Ekskul berhasil dibuat.');
    }

    public function show(Ekskul $ekskul)
    {
      
        $ekskul->load('anggota.profile');
        $isMember = auth()->check() ? $ekskul->anggota->contains(auth()->id()) : false;
        return view('ekskul.show', compact('ekskul','isMember'));
    }

    public function edit(Ekskul $ekskul)
    {
        $this->authorizeEditor();
        return view('ekskul.edit', compact('ekskul'));
    }

    public function update(Request $request, Ekskul $ekskul)
    {
        $this->authorizeEditor();

        $validated = $request->validate([
            'nama_ekskul' => ['required','string','max:100'],
            'deskripsi'   => ['nullable','string'],
            'foto'        => ['nullable','image','mimes:jpg,jpeg,png','max:2048'],
        ]);

        // update field biasa
        $ekskul->nama_ekskul = $validated['nama_ekskul'];
        $ekskul->deskripsi   = $validated['deskripsi'] ?? null;

        // ganti foto jika ada upload baru
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $newName = uniqid().'_'.time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('images/ekskul'), $newName);

            // hapus foto lama (kecuali default)
            if ($ekskul->foto && $ekskul->foto !== 'default.jpg') {
                $old = public_path('images/ekskul/'.$ekskul->foto);
                if (file_exists($old)) @unlink($old);
            }
            $ekskul->foto = $newName;
        }

        $ekskul->save();

        return redirect()->route('ekskul.index')->with('success','Ekskul diperbarui.');
    }

    public function destroy(Ekskul $ekskul)
    {
        $this->authorizeEditor();

        // hapus file foto jika bukan default
        if ($ekskul->foto && $ekskul->foto !== 'default.jpg') {
            $path = public_path('images/ekskul/'.$ekskul->foto);
            if (file_exists($path)) @unlink($path);
        }

        $ekskul->delete();
        return redirect()->route('ekskul.index')->with('success','Ekskul dihapus.');
    }

    /**
     * Siswa bergabung ke ekskul.
     */
    public function join(Ekskul $ekskul)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if(!$user->isSiswa()) {
            return back()->with('error','Hanya siswa yang bisa bergabung.');
        }
        $ekskul->anggota()->syncWithoutDetaching([$user->id]);
        return back()->with('success','Berhasil bergabung ke ekskul.');
    }

    /**
     * Siswa keluar dari ekskul.
     */
    public function leave(Ekskul $ekskul)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if(!$user->isSiswa()) {
            return back()->with('error','Hanya siswa yang bisa keluar sebagai anggota.');
        }
        $ekskul->anggota()->detach($user->id);
        return back()->with('success','Anda telah keluar dari ekskul.');
    }
}
