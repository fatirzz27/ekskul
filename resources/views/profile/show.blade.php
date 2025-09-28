@extends('layouts.master')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <!-- Bagian Header Profile -->
            <div class="d-flex align-items-center mb-4">
                <img src="{{ asset('images/profile/' . ($user->profile->foto ?? 'default.jpg')) }}" 
                class="rounded-circle" width="100" alt="User Avatar">

                <div>
                    <h5 class="mb-0">{{ $user->name ?? '' }}</h5>
                    <small class="text-muted">{{ $user->email ?? '' }}</small>
                    <p class="mb-0">{{ $user->bio ?? '' }}</p>
                </div>
            </div>

            <!-- Bagian Detail Profile -->
            <form>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Full Name</label>
                        <input type="text" class="form-control" value="{{ $user->name ?? '' }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Address</label>
                        <textarea class="form-control" rows="1" readonly>{{ $user->profile->address ?? '' }}</textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Gender</label>
                        <input type="text" class="form-control" value="{{ $user->profile->jenis_kelamin ?? '' }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Nomor HP</label>
                        <input type="text" class="form-control" value="{{ $user->profile->no_hp ?? '' }}" readonly>
                    </div>
                </div>

                <textarea class="form-control" rows="2" readonly>{{ $user->profile->bio ?? '' }}</textarea>

                <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit</a>
            </form>
        </div>
    </div>
</div>
@endsection
