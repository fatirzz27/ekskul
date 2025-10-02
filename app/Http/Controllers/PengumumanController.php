<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengumuman;
use App\Models\Ekskul;
use Illuminate\Support\Facades\Auth; 

class pengumumanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pengumumans = Pengumuman::with('ekskul')->latest()->paginate(9);
        return view('pengumuman.index', compact('pengumumans'));
    }

    public function __construct()
    {
        // Semua butuh login kecuali index & show (untuk siswa/umum)
        $this->middleware('auth')->except(['index','show']);
    }

    private function authorizeEditor()
    {
        // Hanya admin & pembina yang bisa kelola
        if (!in_array(Auth::user()->role, ['admin','pembina'])) {
            abort(403, 'Unauthorized');
        }
    }

    public function manage()
    {
        $this->authorizeEditor();
        $pengumumans = Pengumuman::with('ekskul')->latest()->paginate(9);
        return view('kelola_pengumuman.index', compact('pengumumans'));
    }

    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorizeEditor();
        $ekskuls = Ekskul::all();
        return view('kelola_pengumuman.create', compact('ekskuls'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorizeEditor();

        $validated = $request->validate([
            'judul'     => ['required','string','max:150'],
            'isi'       => ['required','string'],
            'tanggal'   => ['required','date'],
            'ekskul_id' => ['required','exists:ekskuls,id'],
        ]);

        $validated['id_pembuat'] = Auth::id();

        Pengumuman::create($validated);

        return redirect()->route('kelola-pengumuman.manage')->with('success','Pengumuman berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Pengumuman $pengumuman)
    {
        if (in_array(Auth::user()->role ?? 'guest', ['admin','pembina'])) {
        return view('kelola_pengumuman.show', compact('pengumuman'));
    }
    return view('pengumuman.show', compact('pengumuman'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Pengumuman $pengumuman)
    {
        $this->authorizeEditor();
        $ekskuls = Ekskul::all();
        return view('kelola_pengumuman.edit', compact('pengumuman','ekskuls'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pengumuman $pengumuman)
    {
        $this->authorizeEditor();

        $validated = $request->validate([
            'judul'     => ['required','string','max:150'],
            'isi'       => ['required','string'],
            'tanggal'   => ['required','date'],
            'ekskul_id' => ['required','exists:ekskuls,id'],
        ]);

        $pengumuman->update($validated);

        return redirect()->route('kelola-pengumuman.manage')->with('success','Pengumuman berhasil diperbarui.');
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pengumuman $pengumuman)
    {
        $this->authorizeEditor();

        $pengumuman->delete();
        return redirect()->route('kelola-pengumuman.manage')->with('success','Pengumuman berhasil dihapus.');
    }
}
