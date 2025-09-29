@extends('layouts.master')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create New Ekskul</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header text-center">
            <h3>Create New ekskul</h3>
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

            <form action="{{ route('ekskul.store') }}" method="POST" enctype="multipart/form-data">
              @csrf
              
              <!-- Nama Ekskul -->
              <div class="mb-4">
                <div class="row">
                  <div class="col-md-3">
                    <label for="nama_ekskul" class="form-label">Nama ekskul</label>
                  </div>
                  <div class="col-md-9">
                    <input type="text" class="form-control @error('nama_ekskul') is-invalid @enderror" 
                           id="nama_ekskul" name="nama_ekskul" value="{{ old('nama_ekskul') }}" required>
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
                              id="deskripsi" name="deskripsi" rows="5">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
              </div>

              <!-- Upload Foto -->
              <div class="mb-4">
                <div class="row">
                  <div class="col-md-3">
                    <label for="foto" class="form-label">Foto ekskul</label>
                  </div>
                  <div class="col-md-9">
                    <input type="file" class="form-control @error('foto') is-invalid @enderror" 
                           id="foto" name="foto" accept="image/*">
                    <small class="form-text text-muted">Format yang diizinkan: JPG, JPEG, PNG (Max: 2MB)</small>
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
                      <button type="submit" class="btn btn-success px-5">Save</button>
                      <a href="{{ route('ekskul.index') }}" class="btn btn-secondary px-5">Back</a>
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

  <!-- Bootstrap JavaScript -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
@endsection
