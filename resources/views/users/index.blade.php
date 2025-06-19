@extends('layouts1.app')

@section('content')
<div class="row">
    <!-- User table start -->
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h4>Data User</h4>
                <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahModal">Tambah User</button>
            </div>
            <div class="card-body" style="overflow-x:auto;">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                <table id="res-config" class="display table table-striped table-hover dt-responsive nowrap" style="width: 100%">
                    <thead class="table-primary">
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role }}</td>
                            <td>
                                <!-- Tombol Edit -->
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $user->id }}">
                                    <i class="fa fa-pencil-alt"></i> <span class="d-none d-sm-inline">Edit</span>
                                </button>

                                <!-- Form Hapus -->
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button onclick="return confirm('Hapus user ini?')" class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash-alt"></i> <span class="d-none d-sm-inline">Hapus</span>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Modal Edit -->
                        <div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route('users.update', $user->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5>Edit User</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-2">
                                                <label>Nama</label>
                                                <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                                            </div>
                                            <div class="mb-2">
                                                <label>Email</label>
                                                <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                                            </div>
                                            <div class="mb-2">
                                                <label>Role</label>
                                                <select name="role" class="form-select" required>
                                                    <option value="Admin" {{ $user->role == 'Admin' ? 'selected' : '' }}>Admin</option>
                                                    <option value="Owner" {{ $user->role == 'Owner' ? 'selected' : '' }}>Owner</option>
                                                </select>
                                            </div>
                                            <div class="mb-2">
                                                <label>Password (Kosongkan jika tidak ingin mengubah)</label>
                                                <input type="password" name="password" class="form-control">
                                            </div>
                                            <div class="mb-2">
                                                <label>Konfirmasi Password</label>
                                                <input type="password" name="password_confirmation" class="form-control">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-primary">Simpan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="tambahModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Tambah User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>Nama</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Role</label>
                        <select name="role" class="form-select" required>
                            <option value="Admin">Admin</option>
                            <option value="Owner">Owner</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
