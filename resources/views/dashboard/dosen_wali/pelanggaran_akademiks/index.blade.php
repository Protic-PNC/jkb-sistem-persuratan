@extends('dashboard.dosen_wali.layouts.main')

@section('container')
    <div class="container-fluid mb-4 pt-4 px-4">
        <div class="row g-4">
            <div class="col-sm-6 col-xl-6">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-line fa-3x text-primary me-3"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Surat Peringatan karena Pelanggaran Akademik Kelas
                            {{ $kelas->nama_kelas ?? '...' }}</p>
                        <h6 class="mb-0">{{ $totalPelanggaranAkademikKelas }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-6">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-line fa-3x text-primary me-3"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Surat Peringatan karena Pelanggaran Akademik Dosen Wali</p>
                        <h6 class="mb-0">{{ $totalPelanggaranAkademikDosen }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid pt-4 px-3">
        <div class="d-flex justify-content-between align-items-center">
            <select id="filter-type" class="form-select" style="width: 200px;">
                <option value="kelas">Tabel Kelas</option>
                <option value="dosen">Tabel Dosen Wali</option>
            </select>
        </div>
    </div>

    <!-- Sale & Revenue End -->

    <!-- Recent Sales Start -->
    <div class="container-fluid pt-4 px-3">
        <div class="bg-light text-center rounded p-4">
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 id="table-title" class="mb-0" data-kelas="{{ $kelas->nama_kelas ?? '' }}">Daftar Surat Peringatan
                    karena Pelanggaran Akademik Kelas {{ $kelas->nama_kelas ?? '...' }}</h6>
                <a href="/dashboard/dosen-wali/pelanggaran-akademik/create" class="btn btn-primary">Tambah Surat</a>
            </div>

            <div class="table-responsive">
                @include('dashboard.dosen_wali.pelanggaran_akademiks.table')
            </div>
        </div>
    </div>
@endsection
