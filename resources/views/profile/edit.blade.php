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

                    {{-- Header --}}
                    <div class="text-center mb-4">
                        <h4 class="fw-bold">Edit Profile</h4>
                        <img src="https://i.pravatar.cc/100" class="rounded-circle mb-2" width="100" alt="User Avatar">
                        <input type="file" class="form-control form-control-sm" style="max-width: 150px; margin: 0 auto;">
                    </div>

                    {{-- Form --}}
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PATCH')

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
                                   value="{{ old('phone', $user->phone ?? '') }}"
                                   class="form-control">
                        </div>

                        {{-- Gender --}}
                        <div class="mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select name="gender" id="gender" class="form-select">
                                <option value="">-- Select Gender --</option>
                                <option value="Laki" {{ old('gender', $user->gender ?? '') == 'Laki' ? 'selected' : '' }}>Laki</option>
                                <option value="Perempuan" {{ old('gender', $user->gender ?? '') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        {{-- NIS / Nomor ID --}}
                        <div class="mb-3">
                            <label for="nis" class="form-label">NIS</label>
                            <input type="text" name="nis" id="nis" 
                                   value="{{ old('nis', $user->nis ?? '') }}"
                                   class="form-control">
                        </div>

                        {{-- Address --}}
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea name="address" id="address" rows="3" 
                                      class="form-control">{{ old('address', $user->address ?? '') }}</textarea>
                        </div>

                        {{-- Save Button --}}
                        <div class="d-grid">
                           <a href="{{route('profile.show')}}">save change</a>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
