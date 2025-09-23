@extends('layouts.master')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <!-- Bagian Header Profile -->
            <div class="d-flex align-items-center mb-4">
                <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : 'https://via.placeholder.com/80' }}" 
                     class="rounded-circle me-3" width="80" height="80" alt="Profile Picture">

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
                        <textarea class="form-control" rows="1" readonly>{{ $user->address ?? '' }}</textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Gender</label>
                        <input type="text" class="form-control" value="{{ $user->gender ?? '' }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Nomor HP</label>
                        <input type="text" class="form-control" value="{{ $user->phone ?? '' }}" readonly>
                    </div>
                </div>

                <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit</a>
            </form>
        </div>
    </div>
</div>
@endsection
