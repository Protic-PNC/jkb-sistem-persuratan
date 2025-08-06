@extends('dashboard.ketua_jurusan.layouts.main')

@section('container')
    <!-- Rekap Status Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-6 col-md-3 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-start gap-3 p-4">
                    <i class="fa fa-file-alt fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Surat Semua Kelas</p>
                        <h6 class="mb-0">{{ $totalPelanggaranAkademikSemuaKelas }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-start gap-3 p-4">
                    <i class="fa fa-file-alt fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Surat Ketua Jurusan</p>
                        <h6 class="mb-0">{{ $totalPelanggaranAkademikKajur }}</h6>
                    </div>
                </div>
            </div>
            <!-- Surat Diproses -->
            <div class="col-6 col-md-3 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-start gap-3 p-4">
                    <i class="fa fa-clock fa-3x text-warning"></i>
                    <div class="ms-3">
                        <p class="mb-2">Diproses</p>
                        <h6 class="mb-0">{{ $totalDiproses }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-start gap-3 p-4">
                    <i class="fa fa-check-circle fa-3x text-success"></i>
                    <div class="ms-3">
                        <p class="mb-2">Disetujui</p>
                        <h6 class="mb-0">{{ $totalDisetujui }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-start gap-3 p-4">
                    <i class="fa fa-times-circle fa-3x text-danger"></i>
                    <div class="ms-3">
                        <p class="mb-2">Ditolak</p>
                        <h6 class="mb-0">{{ $totalDitolak }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Rekap Status End -->

    <!-- Recent Sales Start -->
    <div class="container-fluid pt-4 px-3">
        <div class="bg-light text-center rounded p-4">
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 id="table-title" class="mb-0">Daftar Surat Peringatan karena Pelanggaran Akademik Semua Kelas</h6>
                <div class="d-flex gap-2">
                    <select id="filter-type" class="form-select" style="width: 200px;">
                        <option value="semua_kelas">Tabel Semua Kelas</option>
                        <option value="ketua_jurusan">Tabel Ketua Jurusan</option>
                    </select>
                    <a href="/dashboard/ketua-jurusan/pelanggaran-akademik/create" class="btn btn-primary"
                        id="btn-tambah-surat" style="display: none;">Tambah Surat</a>
                </div>
            </div>
            <div class="table-responsive">
                @include('dashboard.ketua_jurusan.pelanggaran_akademiks.table')
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filter = document.getElementById('filter-type');
            const tableTitle = document.getElementById('table-title');
            const semua_kelasTable = document.getElementById('semua-kelas-table');
            const ketua_jurusanTable = document.getElementById('ketua-jurusan-table');

            function updateView() {
                if (filter.value === 'semua_kelas') {
                    tableTitle.textContent = 'Daftar Surat Peringatan karena Pelanggaran Akademik Semua Kelas';
                    semua_kelasTable.style.display = '';
                    ketua_jurusanTable.style.display = 'none';
                    document.getElementById('btn-tambah-surat').style.display = 'none';
                } else {
                    tableTitle.textContent = 'Daftar Surat Peringatan karena Pelanggaran Akademik Ketua Jurusan';
                    semua_kelasTable.style.display = 'none';
                    ketua_jurusanTable.style.display = '';
                    document.getElementById('btn-tambah-surat').style.display = '';
                }
            }

            filter.addEventListener('change', updateView);
            updateView();
        });
    </script>
@endsection
