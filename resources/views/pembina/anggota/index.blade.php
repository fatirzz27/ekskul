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
            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $user->id }}">
              <i class="bi bi-trash"></i>
            </button>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="3" class="text-center text-muted">Belum ada anggota.</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <div class="d-flex justify-content-center mt-3">
    {{ $anggota->links('pagination::bootstrap-5') }}
</div>
</div>


<!-- Delete Confirmation Modals -->
@foreach($anggota as $user)
  <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg">
        <div class="modal-body text-center p-5">
          <div class="mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle" style="width: 80px; height: 80px;">
              <i class="bi bi-exclamation-triangle text-warning" style="font-size: 2.5rem;"></i>
            </div>
          </div>
          <h4 class="modal-title fw-bold mb-3" id="deleteModalLabel{{ $user->id }}">Are you sure to delete this?</h4>
          <p class="text-muted mb-4">This action cannot be undone. The member "{{ $user->name }}" will be permanently removed from "{{ $ekskul->nama_ekskul }}".</p>
          <div class="d-flex gap-3 justify-content-center">
            <form action="{{ route('pembina.anggota.destroy', [$ekskul->id, $user->id]) }}" method="POST" class="d-inline">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger px-4 py-2 fw-semibold">
                <i class="bi bi-trash me-2"></i>Delete
              </button>
            </form>
            <button type="button" class="btn btn-secondary px-4 py-2 fw-semibold" data-bs-dismiss="modal">
              <i class="bi bi-arrow-left me-2"></i>Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
@endforeach

<!-- Custom Modal Styles -->
<style>
  .modal-content {
    border-radius: 15px;
    background: white;
  }
  
  .modal-backdrop {
    background-color: rgba(0, 0, 0, 0.5);
  }
  
  .modal .btn-danger {
    background: #dc3545;
    border: none;
    border-radius: 50px;
    box-shadow: 0 2px 10px rgba(220, 53, 69, 0.3);
    transition: all 0.3s ease;
  }
  
  .modal .btn-danger:hover {
    background: #c82333;
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
  }
  
  .modal .btn-secondary {
    background: #6c757d;
    border: none;
    border-radius: 50px;
    transition: all 0.3s ease;
  }
  
  .modal .btn-secondary:hover {
    background: #5a6268;
    transform: translateY(-1px);
  }
</style>
@endsection
