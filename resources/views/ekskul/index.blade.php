@extends('layouts.master')

@section('content')
<div class="container">

    {{-- Header + Tombol Create --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">Daftar Ekskul</h4>
        @if(Auth::user()->role === 'admin')
            <a href="{{ route('ekskul.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> CREATE NEW
            </a>
        @endif
    </div>

    {{-- Layout Card --}}
    <div class="row g-4">
        @forelse($ekskuls as $item)
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    {{-- Foto --}}
                    <img src="{{ $item->foto_url ?? asset('images/ekskul/default.jpg') }}" 
                         class="card-img-top" alt="Foto Ekskul">

                    <div class="card-body d-flex flex-column">
                        {{-- Judul --}}
                        <h5 class="card-title">{{ $item->nama_ekskul }}</h5>
                        {{-- Deskripsi --}}
                        <p class="card-text text-muted">
                            {{ \Illuminate\Support\Str::limit($item->deskripsi, 80) }}
                        </p>

                        {{-- Tombol Aksi --}}
                        <div class="mt-auto d-flex justify-content-between align-items-center">
                            <a href="{{ route('ekskul.show', $item->id) }}" class="btn btn-sm btn-primary">
                                READ MORE
                            </a>
                            <div class="d-flex gap-2">
                                @if(in_array(Auth::user()->role, ['admin','pembina']))
                                    <a href="{{ route('ekskul.edit', $item->id) }}" class="btn btn-sm btn-warning text-white">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('ekskul.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus ekskul ini?')">
                                        @csrf
                                        @elseif(Auth::user()->role === 'admin')
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">Belum ada ekskul tersedia.</p>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $ekskuls->links() }}
    </div>

</div>
@endsection
