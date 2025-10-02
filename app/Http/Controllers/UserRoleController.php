<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Ekskul;

class UserRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $query = User::query();

    // kalau ada input pencarian
    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    $users = $query->get();

    return view('KelolaUser.tampil', compact('users'));
}


    /**
     * Update the specified user's role.
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->role = $request->input('role');
        $user->save();

        return redirect()->back()->with('success', 'User role updated!');
    }

    /**
     * Remove the specified user.
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();

        return redirect()->back()->with('success', 'User deleted!');
    }

   public function editEkskul($id)
{
    $user = User::findOrFail($id);
    $ekskuls = Ekskul::all(); // semua ekskul

    return view('KelolaUser.edit', compact('user', 'ekskuls'));
}

public function updateEkskul(Request $request, $id)
{
    $user = User::findOrFail($id);

    // ambil input ekskul_id[] dari form (checklist)
    $ekskulIds = $request->input('ekskul_id', []);

    // sync -> supaya update many-to-many (hapus yang tidak dicentang, simpan yang dicentang)
    $user->ekskul()->sync($ekskulIds);

    return redirect()->route('kelola-user')->with('success', 'Ekskul pembina berhasil diperbarui!');
}

}
