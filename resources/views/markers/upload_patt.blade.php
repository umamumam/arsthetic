@extends('layouts1.app')

@section('content')
<div class="container-fluid">
    <!-- Card untuk Upload File -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary">
            <h5 class="m-0 font-weight-bold text-white">Upload File .patt</h5>
        </div>
        <div class="card-body">
            <form id="uploadForm" enctype="multipart/form-data" class="mb-4">
                @csrf
                <div class="form-group">
                    <label for="patt_file" class="font-weight-bold">Pilih File .patt</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="patt_file" name="patt_file" accept=".patt" required>
                        <label class="custom-file-label" for="patt_file">Choose file</label>
                    </div>
                    <small class="form-text text-muted">
                        File pattern marker untuk AR (maksimal 10MB)
                    </small>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-upload"></i> Upload
                </button>
            </form>

            <div id="uploadResult"></div>
        </div>
    </div>

    <!-- Card untuk Daftar File -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary">
            <h5 class="m-0 font-weight-bold text-white">File .patt yang Tersedia</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="pattTable" class="display table table-striped table-hover dt-responsive nowrap" style="width:100%">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Nama File</th>
                            <th>Ukuran</th>
                            <th>Terakhir Diubah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pattFiles as $index => $file)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $file['name'] }}</td>
                            <td>{{ $file['size'] }}</td>
                            <td>{{ $file['modified_at'] }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ $file['url'] }}" class="btn btn-info btn-sm" download title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <button class="btn btn-danger btn-sm delete-patt" data-filename="{{ $file['name'] }}" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada file .patt</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Load jQuery dan DataTables -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

<script>
// Inisialisasi DataTable
$(document).ready(function() {
    $('#pattTable').DataTable({
        responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json'
        }
    });

    // Update nama file yang dipilih di input file
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });

    // Fungsi untuk menangani form upload
    $('#uploadForm').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        $('#uploadResult').html('<div class="alert alert-info">Mengupload file...</div>');

        $.ajax({
            url: "{{ route('patt.upload') }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $('#uploadResult').html(`
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> ${response.message}
                            <br><strong>File:</strong> ${response.file_name}
                            <br><strong>Ukuran:</strong> ${response.file_info.size}
                        </div>
                    `);
                    setTimeout(() => location.reload(), 1500);
                } else {
                    $('#uploadResult').html(`
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i> ${response.message}
                        </div>
                    `);
                }
            },
            error: function(xhr) {
                $('#uploadResult').html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> Terjadi kesalahan: ${xhr.responseJSON?.message || 'Server error'}
                    </div>
                `);
            }
        });
    });

    // Fungsi untuk menghapus file
    $('.delete-patt').on('click', function() {
        if (!confirm('Yakin ingin menghapus file ini?')) return;

        var filename = $(this).data('filename');
        var $row = $(this).closest('tr');

        $.ajax({
            url: "{{ route('patt.delete') }}",
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                filename: filename
            },
            success: function(response) {
                if (response.success) {
                    $row.fadeOut(400, function() {
                        $(this).remove();
                        if ($('#pattTable tbody tr').length === 0) {
                            $('#pattTable tbody').html('<tr><td colspan="5" class="text-center">Belum ada file .patt</td></tr>');
                        }
                    });
                    showAlert('success', response.message);
                } else {
                    showAlert('danger', response.message);
                }
            }
        });
    });

    function showAlert(type, message) {
        $('#uploadResult').html(`
            <div class="alert alert-${type}">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i> ${message}
            </div>
        `);
    }
});
</script>

<style>
.card {
    border-radius: 10px;
}
.card-header {
    border-radius: 10px 10px 0 0 !important;
}
.custom-file-label::after {
    content: "Browse";
}
.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
</style>
@endsection
