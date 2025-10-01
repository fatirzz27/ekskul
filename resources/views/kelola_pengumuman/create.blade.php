@extends('layouts.master')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header text-center">
                    <h3>Create New Pengumuman</h3>
                </div>
                <div class="card-body">

                    <!-- Success/Error Messages -->
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <!-- Form Create Pengumuman -->
                    <form action="{{ route('kelola-pengumuman.store') }}" method="POST">
                        @csrf

                        <!-- Judul Pengumuman -->
                        <div class="mb-4 row">
                            <label for="judul" class="col-md-3 col-form-label">Judul Pengumuman</label>
                            <div class="col-md-9">
                                <input type="text" name="judul" id="judul"
                                    class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul') }}"
                                    required>
                                @error('judul')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Jenis Ekskul -->

                        <div class="mb-4 row">
                            <label for="ekskul_id" class="col-md-3 col-form-label">Pilih Ekskul</label>
                            <div class="col-md-9">
                            <select name="ekskul_id" id="ekskul_id"
                                class="form-select @error('ekskul-id') is-invalid @enderror" required>
                                <option value="">-- Pilih Ekskul --</option>
                                @foreach($ekskuls as $ekskul)
                                <option value="{{ $ekskul->id }}"
                                    {{ old('ekskul_id') == $ekskul->id ? 'selected' : '' }}>
                                    {{ $ekskul->nama_ekskul }}
                                </option>
                                @endforeach
                            </select>
                            @error('ekskul_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        </div>


                        <!-- Tanggal -->
                        <div class="mb-4 row">
                            <label for="tanggal" class="col-md-3 col-form-label">Tanggal</label>
                            <div class="col-md-9">
                                <input type="date" name="tanggal" id="tanggal"
                                    class="form-control @error('tanggal') is-invalid @enderror"
                                    value="{{ old('tanggal') }}" required>
                                @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Isi -->
                        <div class="mb-4 row">
                            <label for="isi" class="col-md-3 col-form-label">Isi</label>
                            <div class="col-md-9">
                                <textarea name="isi" id="isi" rows="5"
                                    class="form-control @error('isi') is-invalid @enderror"
                                    required>{{ old('isi') }}</textarea>
                                @error('isi')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="mb-3 row">
                            <div class="col-md-3"></div>
                            <div class="col-md-9">
                                <div class="d-flex gap-3">
                                    <button type="submit" class="btn btn-success px-5">Save</button>
                                    <a href="{{ route('kelola-pengumuman.manage') }}"
                                        class="btn btn-secondary px-5">Back</a>
                                </div>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection