@extends('layouts1.app')

@section('content')
<div class="row">
    <!-- Photobooth table start -->
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h4>Data Photobooth</h4>
                <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahModal">Tambah Photobooth</button>
            </div>
            <div class="card-body" style="overflow-x:auto;">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <table id="res-config" class="display table table-striped table-hover dt-responsive nowrap" style="width: 100%">
                    <thead class="table-primary">
                        <tr>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($photobooths as $pb)
                        <tr>
                            <td>{{ $pb->nama }}</td>
                            <td>{{ $pb->alamat }}</td>
                            <td>
                                <!-- Tombol Edit -->
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $pb->id }}">
                                    <i class="fa fa-pencil-alt"></i> <span class="d-none d-sm-inline">Edit</span>
                                </button>

                                <!-- Form Hapus -->
                                <form action="{{ route('photobooths.destroy', $pb->id) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button onclick="return confirm('Hapus data ini?')" class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash-alt"></i> <span class="d-none d-sm-inline">Hapus</span>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Modal Edit -->
                        <div class="modal fade" id="editModal{{ $pb->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route('photobooths.update', $pb->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5>Edit Photobooth</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-2">
                                                <label>Nama</label>
                                                <input type="text" name="nama" class="form-control" value="{{ $pb->nama }}" required>
                                            </div>
                                            <div class="mb-2">
                                                <label>Alamat</label>
                                                <textarea name="alamat" class="form-control" required>{{ $pb->alamat }}</textarea>
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
        <form action="{{ route('photobooths.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Tambah Photobooth</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>Nama</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Alamat</label>
                        <textarea name="alamat" class="form-control" required></textarea>
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
