@extends('dashboard.admin.layouts.main')

@section('container')
    <div class="container-fluid mb-4 pt-4 px-4">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-6">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">Upload File PDF Surat Pengunduran Diri</h6>
                    <div id="form-container">
                        <form id="upload-form" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="file_pdf" class="form-label">File PDF</label>
                                <input type="file" class="form-control" id="file_pdf" name="file_pdf" accept=".pdf" required>
                                <div class="form-text">File dalam format PDF, maksimal 2MB.</div>
                            </div>
                            <a href="/dashboard/admin/pengunduran-diri" class="btn btn-success"><i
                                    class="bi bi-arrow-left-square"></i> Kembali</a>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-upload"></i> Upload</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#upload-form').on('submit', function(e) {
                e.preventDefault();
                
                var formData = new FormData(this);
                
                $.ajax({
                    url: '/dashboard/admin/pengunduran-diri/{{ $pengundurans->noSurat }}/upload',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Sedang Memproses...',
                            html: 'Mohon tunggu.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: response.status,
                            title: response.status === 'success' ? 'Berhasil!' : 'Informasi',
                            text: response.message
                        }).then(() => {
                            if (response.status === 'success') {
                                window.location.href = '/dashboard/admin/pengunduran-diri';
                            }
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = 'Terjadi kesalahan. Mohon coba lagi.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;
                            const firstError = Object.values(errors)[0][0];
                            errorMessage = firstError;
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: errorMessage
                        });
                    }
                });
            });
        });
    </script>
@endsection 