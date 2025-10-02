@extends('layouts.master')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-body">
            <h3 class="card-title">{{ $pengumuman->judul }}</h3>
            <p ><strong>Tanggal :</strong> {{ $pengumuman->tanggal }}</p>
            <p >
                <strong>Dibuat oleh :</strong> 
                {{ $pengumuman->pembuat->name ?? 'Tidak diketahui' }}
            </p>

            <p class="mt-3"> <strong>Pesan : </strong>{{ $pengumuman->isi }}</p>

            

            <a href="{{ route('pengumuman.index') }}" class="btn btn-secondary mt-3">Kembali</a>
        </div>
    </div>
</div>
@endsection
