@extends('layouts1.app')

@section('content')
<div class="row">
    <!-- Marker table start -->
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h4>Daftar Marker</h4>
                <a href="{{ url('/markers/create') }}" class="btn btn-success mb-3">Tambah Marker</a>
                <a href="{{ url('/ar/scan') }}" class="btn btn-primary mb-3">Lihat AR Scan</a>
            </div>
            <div class="card-body" style="overflow-x:auto;">
                @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <table id="res-config" class="display table table-striped table-hover dt-responsive nowrap"
                    style="width: 100%">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Foto</th>
                            {{-- <th>Video</th> --}}
                            <th>Tgl. Upload</th>
                            <th>Lokasi Photobooth</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach($markers as $marker)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $marker->unique_code }}</td>
                            <td>
                                <img src="{{ asset('storage/' . $marker->photos->first()->path) }}" width="100"
                                    height="100">
                            </td>
                            {{-- <td>
                                <video width="150" height="150" controls>
                                    <source src="{{ Storage::url($marker->video_path) }}">
                                </video>
                            </td> --}}
                            <td>{{ $marker->created_at->format('d F Y H:i') }}</td>
                            <td>{{ $marker->photobooth->nama }}</td>
                            <td>
                                <!-- Tombol Show -->
                                <a href="{{ url('/markers/'.$marker->id) }}" class="btn btn-info btn-sm" title="Detail">
                                    <i class="fa fa-eye"></i> <span class="d-none d-sm-inline">Show</span>
                                </a>

                                <!-- Tombol Edit -->
                                <a href="{{ url('/markers/'.$marker->id.'/edit') }}" class="btn btn-warning btn-sm"
                                    title="Edit">
                                    <i class="fa fa-pencil-alt"></i> <span class="d-none d-sm-inline">Edit</span>
                                </a>

                                <!-- Form Hapus -->
                                <form action="{{ url('/markers/'.$marker->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Yakin hapus marker ini?')"
                                        class="btn btn-danger btn-sm" title="Hapus">
                                        <i class="fa fa-trash-alt"></i> <span class="d-none d-sm-inline">Hapus</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
