@extends('layouts.master')

@section('content')
<div class="container py-4">
  <h3 class="fw-bold mb-4">Anggota Ekskul: {{ $ekskul->nama_ekskul }}</h3>

  <a href="{{ route('pembina.anggota.index') }}" class="btn btn-secondary mb-3">&larr; Kembali</a>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  @if($anggota->count())
    <ul class="list-group">
      @foreach($anggota as $user)
        <li class="list-group-item d-flex justify-content-between align-items-center">
          {{ $user->name }}
          <form action="{{ route('pembina.anggota.destroy', [$ekskul->id, $user->id]) }}" method="POST" onsubmit="return confirm('Yakin hapus anggota ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
          </form>
        </li>
      @endforeach
    </ul>
  @else
    <p class="text-muted fst-italic">Belum ada anggota.</p>
  @endif
</div>



@endsection
    