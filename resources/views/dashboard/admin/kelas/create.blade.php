@extends('dashboard.admin.layouts.main')

@section('container')
    <div class="container-fluid mb-4 pt-4 px-4">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-6">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">Tambah Kelas</h6>
                    <form id="create-form-kelas" method="post" action="/dashboard/admin/kelas">
                        @csrf
                        <div class="mb-3">
                            <label for="nama_kelas" class="form-label">Nama Kelas</label>
                            <input type="text" class="form-control @error('nama_kelas') is-invalid @enderror"
                                id="nama_kelas" name="nama_kelas" value="{{ old('nama_kelas') }}">
                            @error('nama_kelas')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="username_dosen_wali" class="form-label">Username Dosen Wali</label>
                            <input type="text" class="form-control @error('username_dosen_wali') is-invalid @enderror"
                                id="username_dosen_wali" name="username_dosen_wali"
                                value="{{ old('username_dosen_wali') }}">
                            @error('username_dosen_wali')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <a href="/dashboard/admin/kelas" class="btn btn-success"><i class="bi bi-arrow-left-square"></i>
                            Kembali</a>
                        <button type="button" class="btn btn-primary" onclick="saveKelas()"><i
                                class="bi bi-check2 me-1"></i>Buat Kelas</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
