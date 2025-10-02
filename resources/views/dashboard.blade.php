@extends('layouts.master')

@section('content')
    <div class="row">
        @foreach($ekskuls as $ekskul)
            <div class="col-md-4">
                <div class="card shadow-sm mb-4">
                    <img src="{{ asset('images/ekskul/' . $ekskul->foto) }}" class="card-img-top" alt="{{ $ekskul->nama }}" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title">{{ $ekskul->nama }}</h5>
                        <p class="card-text">{{ Str::limit($ekskul->deskripsi ?? 'Tidak ada deskripsi', 100, '...') }}</p>
                        <a href="{{ route('ekskul.show', $ekskul->id) }}" class="btn btn-primary">Lihat Detail</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
