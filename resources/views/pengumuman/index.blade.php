@extends('layouts.master')

@section('content')
<div class="container">

    {{-- Pesan sukses --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Kalau belum ada pengumuman --}}
    @if($pengumumans->isEmpty())
        <div class="col-12">
            <div class="alert alert-info text-center">
                <h5>Belum ada pengumuman</h5>
                <p>Silakan cek kembali nanti.</p>
            </div>
        </div>
    @else
        <div class="row">
            @foreach($pengumumans as $p)
            <div class="col-md-4">
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $p->judul }}</h5>
                        <p class="card-text">{{ Str::limit($p->isi, 100) }}</p>
                        <small class="text-muted">Tanggal: {{ $p->tanggal }}</small>
                        
                        <div class="mt-3">
                            <a href="{{ route('pengumuman.show', $p->id) }}" class="btn btn-primary btn-sm">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center mt-3">
            {{ $pengumumans->links() }}
        </div>
    @endif
</div>
@endsection
