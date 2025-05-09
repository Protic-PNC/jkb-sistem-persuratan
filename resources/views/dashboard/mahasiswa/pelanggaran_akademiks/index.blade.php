@extends('dashboard.mahasiswa.layouts.main')

@section('container')
<!-- Rekap Status Start -->
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-6 col-md-3 col-xl-3">
            <div class="bg-light rounded d-flex align-items-center justify-content-start gap-3 p-4">
                <i class="fa fa-file-alt fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2">Total Surat</p>
                    <h6 class="mb-0">{{ $totalPelanggaranAkademik }}</h6>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl-3">
            <div class="bg-light rounded d-flex align-items-center justify-content-start gap-3 p-4">
                <i class="fa fa-clock fa-3x text-warning"></i>
                <div class="ms-3">
                    <p class="mb-2">Diproses</p>
                    <h6 class="mb-0">{{ $totalDiproses ?? 0 }}</h6>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl-3">
            <div class="bg-light rounded d-flex align-items-center justify-content-start gap-3 p-4">
                <i class="fa fa-check-circle fa-3x text-success"></i>
                <div class="ms-3">
                    <p class="mb-2">Disetujui</p>
                    <h6 class="mb-0">{{ $totalDisetujui ?? 0 }}</h6>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl-3">
            <div class="bg-light rounded d-flex align-items-center justify-content-start gap-3 p-4">
                <i class="fa fa-times-circle fa-3x text-danger"></i>
                <div class="ms-3">
                    <p class="mb-2">Ditolak</p>
                    <h6 class="mb-0">{{ $totalDitolak ?? 0 }}</h6>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Rekap Status End -->

<!-- Recent Sales Start -->
<div class="container-fluid mb-4 pt-4 px-3">
    <div class="bg-light text-center rounded p-4">
        @if(session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">Daftar Surat Pelanggaran Akademik</h6>
        </div>

        <div class="table-responsive">
            @include('dashboard.mahasiswa.pelanggaran_akademiks.table')
        </div>
    </div>
</div>
@endsection
