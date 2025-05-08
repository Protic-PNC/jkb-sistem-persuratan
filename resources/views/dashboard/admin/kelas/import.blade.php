@extends('dashboard.admin.layouts.main')

@section('container')
    <div class="container-fluid mb-4 pt-4 px-4 d-flex justify-content-center align-items-center">
        <div class="row g-4 w-100">
            <div class="col-sm-12 col-xl-6 mx-auto">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">Upload CSV</h6>
                    <form id="import-form-kelas" action="/dashboard/admin/kelas/import" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <a href="{{ route('kelas.download-template') }}" class="btn btn-outline-secondary btn-sm"
                                download>
                                <i class="bi bi-file-earmark-arrow-down"></i> Unduh Template CSV
                            </a>
                        </div>
                        <div class="mb-3">
                            <label for="csv_file" class="form-label">Pilih File CSV</label>
                            <input type="file" class="form-control @error('csv_file') is-invalid @enderror"
                                id="csv_file" name="csv_file">
                            @error('csv_file')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <a href="/dashboard/admin/kelas" class="btn btn-success"><i class="bi bi-arrow-left-square"></i>
                            Kembali</a>
                        <button type="submit" class="btn btn-primary" onclick="event.preventDefault(); importKelasCSV()"><i
                                class="bi bi-check2 me-1"></i>Import
                            CSV</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
