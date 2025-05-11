@extends('dashboard.dosen_wali.layouts.main')

@section('container')
    <!-- Rekap Status Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-6 col-md-3 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-start gap-3 p-4">
                    <i class="fa fa-file-alt fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Surat Kelas {{ $kelas->nama_kelas ?? '...' }}</p>
                        <h6 class="mb-0">{{ $totalPelanggaranAkademikKelas }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-start gap-3 p-4">
                    <i class="fa fa-file-alt fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Surat Dosen Wali</p>
                        <h6 class="mb-0">{{ $totalPelanggaranAkademikDosen }}</h6>
                    </div>
                </div>
            </div>
            <!-- Surat Belum Selesai -->
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
                <h6 id="table-title" class="mb-0" data-kelas="{{ $kelas->nama_kelas ?? '' }}">Daftar Surat Peringatan
                    karena Pelanggaran Akademik Kelas {{ $kelas->nama_kelas ?? '...' }}</h6>
                <div class="d-flex gap-2">
                    <select id="filter-type" class="form-select" style="width: 200px;">
                        <option value="kelas">Tabel Kelas</option>
                        <option value="dosen">Tabel Dosen Wali</option>
                    </select>
                    <a href="/dashboard/dosen-wali/pelanggaran-akademik/create" class="btn btn-primary"
                        id="btn-tambah-surat" style="display:none;">Tambah Surat</a>
                </div>
            </div>
            <div class="table-responsive">
                @include('dashboard.dosen_wali.pelanggaran_akademiks.table')
            </div>
        </div>
    </div>
    <script>
        // Tampilkan tombol tambah surat hanya jika filter dosen
        document.addEventListener('DOMContentLoaded', function() {
            const filter = document.getElementById('filter-type');
            const btnTambah = document.getElementById('btn-tambah-surat');

            function toggleBtn() {
                btnTambah.style.display = filter.value === 'dosen' ? '' : 'none';
            }
            filter.addEventListener('change', toggleBtn);
            toggleBtn();
        });
    </script>
@endsection
