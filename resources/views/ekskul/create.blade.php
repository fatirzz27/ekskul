@extends('layouts.master')

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="mb-3">Tambah Ekskul</h5>
        <form action="{{ route('ekskul.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nama Ekskul</label>
                <input type="text" name="nama_ekskul" class="form-control" value="{{ old('nama_ekskul') }}" required>
                @error('nama_ekskul') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" rows="4" class="form-control">{{ old('deskripsi') }}</textarea>
                @error('deskripsi') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Foto (jpg/jpeg/png, maks 2MB)</label>
                <input type="file" name="foto" class="form-control">
                @error('foto') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="d-flex gap-2">
                <button class="btn btn-primary">Simpan</button>
                <a href="{{ route('ekskul.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
