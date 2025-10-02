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
  <table class="table table-bordered table-striped">f
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
        
            <form action="{{ route('user.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger btn-sm">
                <i class="bi bi-trash"></i>
              </button>
              @if ($user->role == 'pembina')
                <a href="{{ route('user.editEkskul', $user->id) }}" class="btn btn-primary btn-sm">
                <i class="bi bi-pencil"></i>
                </a>
                @endif
            </form>
          </td>
        </tr>
        @endif
      @endforeach
    </tbody>
  </table>
</div>
@endsection
