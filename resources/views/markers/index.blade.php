@extends('layouts1.app')

@section('content')
<div class="container">
    <h2>Daftar Marker</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ url('/markers/create') }}" class="btn btn-success mb-3">Tambah Marker</a>
    <a href="{{ url('/ar/scan') }}" class="btn btn-primary mb-3">Lihat AR Scan</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Foto</th>
                <th>Video</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($markers as $marker)
                <tr>
                    <td>{{ $marker->unique_code }}</td>
                    <td>
                        <img src="{{ Storage::url($marker->photo_path) }}" width="100">
                    </td>
                    <td>
                        <video width="150" controls>
                            <source src="{{ Storage::url($marker->video_path) }}">
                        </video>
                    </td>
                    <td>{{ $marker->description }}</td>
                    <td>
                        <a href="{{ url('/markers/'.$marker->id.'/edit') }}" class="btn btn-warning btn-sm">Edit</a>

                        <form action="{{ url('/markers/'.$marker->id) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus marker ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
