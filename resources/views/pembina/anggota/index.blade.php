@extends('layouts.master')

@section('content')
<div class="container py-4">
  <h3 class="mb-4 fw-bold">Anggota Ekskul: {{ $ekskul->nama_ekskul }}</h3>

  <table class="table table-bordered table-striped">
    <thead class="table-dark">
      <tr>
        <th scope="col">Anggota</th>
        <th scope="col">Nomor HP</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
      @forelse($anggota as $user)
        <tr>
          <td>
            <div class="d-flex align-items-center">
            @if(!empty($user->profile->foto))
  <img src="{{ asset('images/profile/' . $user->profile->foto) }}"
       alt="Foto {{ $user->name }}"
       class="rounded-circle me-2"
       style="width: 35px; height: 35px; object-fit: cover;">
@else
  <span class="me-2" style="font-size: 1.5rem;">👤</span>
@endif
              <span>{{ $user->name }}</span>
            </div>
          </td>
          <td>{{ $user->profile->no_hp ?? '-' }}</td>
          <td>
            <form action="{{ route('pembina.anggota.destroy', [$ekskul->id, $user->id]) }}" 
                  method="POST" class="d-inline">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger btn-sm">
                <i class="bi bi-trash"></i>
              </button>
            </form>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="3" class="text-center text-muted">Belum ada anggota.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
