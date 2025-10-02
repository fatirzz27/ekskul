@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            {{-- Tombol Back --}}
            <a href="{{ route('profile.show') }}" class="btn btn-link text-dark mb-3">
                <i class="bi bi-arrow-left fs-4"></i>
            </a>

            {{-- Card Edit Profile --}}
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">

                    <div class="text-center mb-4">
                        <h4 class="fw-bold">Edit Profile</h4>

                        {{-- Foto saat ini --}}
                        <img
                            src="{{ asset('images/profile/' . ($user->profile->foto ?? 'default.jpg')) }}"
                            class="rounded-circle mb-2"
                            width="100"
                            height="100"
                            style="object-fit: cover;"
                            alt="User Avatar">

                    </div>

                    {{-- SATU FORM untuk semua field + foto --}}
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        {{-- Upload Foto --}}
                        <div class="mb-3">
                            <label class="form-label">Ubah Foto Profil</label>
                            <input type="file" name="foto" class="form-control form-control-sm">
                            <small class="text-muted">jpg, jpeg, png • maks 2MB</small>
                        </div>

                        {{-- Nama --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" name="name" id="name"
                                   value="{{ old('name', $user->name) }}"
                                   class="form-control">
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email"
                                   value="{{ old('email', $user->email) }}"
                                   class="form-control">
                        </div>

                        {{-- Phone --}}
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" name="phone" id="phone"
                                   value="{{ old('phone', $user->profile->no_hp ?? '') }}"
                                   class="form-control">
                        </div>

                        {{-- Gender --}}
                        <div class="mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select name="gender" id="gender" class="form-select">
                                <option value="">-- Pilih --</option>
                                <option value="L" {{ old('gender', $user->profile->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki</option>
                                <option value="P" {{ old('gender', $user->profile->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        {{-- Address --}}
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea name="address" id="address" rows="3" class="form-control">{{ old('address', $user->profile->address ?? '') }}</textarea>
                        </div>

                        {{-- Bio --}}
                        <div class="mb-3">
                            <label for="bio" class="form-label">Bio</label>
                            <textarea name="bio" id="bio" rows="3" class="form-control">{{ old('bio', $user->profile->bio ?? '') }}</textarea>
                        </div>

                        {{-- Save Button --}}
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Save Change</button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
