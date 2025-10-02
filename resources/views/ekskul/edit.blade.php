@extends('layouts.master')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Ekskul - {{ $ekskul->nama_ekskul }}</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header text-center">
            <h3>Edit Ekskul</h3>
          </div>
          <div class="card-body">
            <form action="{{ route('ekskul.update', $ekskul) }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              
              <!-- Nama Ekskul -->
              <div class="mb-4">
                <div class="row">
                  <div class="col-md-3">
                    <label for="nama_ekskul" class="form-label">Nama ekskul</label>
                  </div>
                  <div class="col-md-9">
                    <input type="text" class="form-control @error('nama_ekskul') is-invalid @enderror" 
                           id="nama_ekskul" name="nama_ekskul" value="{{ old('nama_ekskul', $ekskul->nama_ekskul) }}" required>
                    @error('nama_ekskul')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
              </div>

              <!-- Deskripsi Ekskul -->
              <div class="mb-4">
                <div class="row">
                  <div class="col-md-3">
                    <label for="deskripsi" class="form-label">Dekskripsi ekskul</label>
                  </div>
                  <div class="col-md-9">
                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                              id="deskripsi" name="deskripsi" rows="5">{{ old('deskripsi', $ekskul->deskripsi) }}</textarea>
                    @error('deskripsi')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
              </div>

              <!-- Foto Saat Ini -->
              @if($ekskul->foto)
                <div class="mb-4">
                  <div class="row">
                    <div class="col-md-3">
                      <label class="form-label">Foto saat ini</label>
                    </div>
                    <div class="col-md-9">
                      <img src="{{ $ekskul->foto_url }}" alt="{{ $ekskul->nama_ekskul }}" 
                           class="img-thumbnail" style="max-width: 200px; max-height: 150px; object-fit: cover;">
                    </div>
                  </div>
                </div>
              @endif

              <!-- Upload Foto Baru -->
              <div class="mb-4">
                <div class="row">
                  <div class="col-md-3">
                    <label for="foto" class="form-label">Foto baru (opsional)</label>
                  </div>
                  <div class="col-md-9">
                    <input type="file" class="form-control @error('foto') is-invalid @enderror" 
                           id="foto" name="foto" accept="image/*">
                    <small class="form-text text-muted">Format yang diizinkan: JPG, JPEG, PNG (Max: 2MB). Kosongkan jika tidak ingin mengganti foto.</small>
                    @error('foto')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
              </div>

              <!-- Buttons -->
              <div class="mb-3">
                <div class="row">
                  <div class="col-md-3"></div>
                  <div class="col-md-9">
                    <div class="d-flex gap-3">
                      <button type="submit" class="btn btn-success px-5">Update</button>
                      <a href="{{ route('ekskul.show', $ekskul) }}" class="btn btn-secondary px-5">Cancel</a>
                    </div>
                  </div>
                </div>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
@endsection
