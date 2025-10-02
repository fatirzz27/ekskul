@extends('layouts.master')

@section('content')

<div class="container mt-4">
  <h3 class="mb-3">Kelola Users</h3>
  <form action="{{ route('kelola-user') }}" method="GET" class="mb-3 d-flex" role="search">
    <input type="text" name="search" class="form-control me-2" 
           placeholder="Cari nama atau email..."
           value="{{ request('search') }}">
    <button type="submit" class="btn btn-primary">Search</button>
  </form>
  <table class="table table-bordered table-striped">
    <thead class="table-dark">
      <tr>
        <th scope="col">Users</th>
        <th scope="col">Email</th>
        <th scope="col">Role</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($users as $user)
        @if ($user->role != 'admin')
        <tr>
          <td>{{ $user->name }}</td>
          <td>{{ $user->email }}</td>
          <td>
        <form action="{{ route('user.update', $user->id) }}" method="POST">
          @csrf
          @method('PUT')
          <select name="role" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="siswa" @selected($user->role == 'siswa')>Siswa</option>
            <option value="pembina" @selected($user->role == 'pembina')>Pembina</option>
          </select>
        </form>
          </td>
          <td>
            
            
            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $user->id }}">
              <i class="bi bi-trash"></i>
            </button>
            @if ($user->role == 'pembina')
              <a href="{{ route('user.editEkskul', $user->id) }}" class="btn btn-primary btn-sm me-2">
                <i class="bi bi-pencil"></i>
              </a>
            @endif
          </td>
        </tr>
        @endif
        
      @endforeach
    </tbody>
  </table>
</div>
<div class="d-flex justify-content-center mt-3">
    {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
</div>


<!-- Delete Confirmation Modals -->
@foreach ($users as $user)
  @if ($user->role != 'admin')
    <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $user->id }}" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
          <div class="modal-body text-center p-5">
            <div class="mb-4">
              <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle" style="width: 80px; height: 80px;">
                <i class="bi bi-exclamation-triangle text-warning" style="font-size: 2.5rem;"></i>
              </div>
            </div>
            <h4 class="modal-title fw-bold mb-3" id="deleteModalLabel{{ $user->id }}">Are you sure to delete this user?</h4>
            <p class="text-muted mb-4">This action cannot be undone. The user "<strong>{{ $user->name }}</strong>" will be permanently deleted.</p>
            <div class="d-flex gap-3 justify-content-center">
              <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="d-inline">
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
  @endif
@endforeach

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
  /* Custom Modal Styles */
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
