<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest; // kalau Anda pake FormRequest ini
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profile (read-only / show).
     */
    public function show(): View
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    /**
     * Tampilkan form edit profile.
     */
    public function edit(): View
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update profile.
     * Jika Anda tidak menggunakan ProfileUpdateRequest, ganti tipenya ke Request dan lakukan validasi manual.
     */
    public function update(Request $request): RedirectResponse
{
    $user = $request->user();

    $request->validate([
        'name'   => ['required','string','max:255'],
        'email'  => ['required','email','max:255'],
        'phone'  => ['nullable','string','max:50'],
        'gender' => ['nullable','in:L,P'],
        'address'=> ['nullable','string','max:255'],
        'bio'    => ['nullable','string'],
        'foto'   => ['nullable','image','mimes:jpg,jpeg,png','max:2048'],
    ]);

    // Update users table
    $user->name  = $request->name;
    $user->email = $request->email;
    $user->save();

    // Pastikan profile ada
    $profile = $user->profile()->firstOrCreate([]);

    // Update profile fields
    $profile->no_hp         = $request->phone;
    $profile->jenis_kelamin = $request->gender;
    $profile->address       = $request->address;
    $profile->bio           = $request->bio;

    // Handle foto (opsional)
    if ($request->hasFile('foto')) {
        $file = $request->file('foto');

        // Hapus foto lama jika ada dan bukan default
        if ($profile->foto && $profile->foto !== 'default.jpg') {
            $oldPath = public_path('images/profile/'.$profile->foto);
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
        }

        // Simpan foto baru ke public/images/profile
        $filename = uniqid().'_'.$user->id.'.'.$file->getClientOriginalExtension();
        $file->move(public_path('images/profile'), $filename);

        $profile->foto = $filename;
    }

    $profile->save();

    return Redirect::route('profile.show')->with('status', 'Profile berhasil diperbarui');
}


    /**
     * Hapus akun.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

   
}
