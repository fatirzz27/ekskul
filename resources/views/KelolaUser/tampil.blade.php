@extends('layouts.master')

@section('content')
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
    <tr>
      <td>John Doe</td>
      <td>john@example.com</td>
      <td>
        <select class="form-select form-select-sm" aria-label="Select role">
          <option selected>User</option>
          <option value="1">Admin</option>
          <option value="2">Editor</option>
          <option value="3">Viewer</option>
        </select>
      </td>
      <td>
 <button class="btn btn-danger btn-sm">
          <i class="bi bi-trash"></i>
        </button>

      </td>
    </tr>
    <tr>
      <td>Jane Smith</td>
      <td>jane@example.com</td>
      <td>
        <select class="form-select form-select-sm" aria-label="Select role">
          <option selected>Admin</option>
          <option value="1">Editor</option>
          <option value="2">Viewer</option>
        </select>
      </td>
      <td>
        <button class="btn btn-danger btn-sm">
          <i class="bi bi-trash"></i>
        </button>
      </td>
    </tr>
  </tbody>
</table>

@endsection
