@extends('dashboard.mahasiswa.layouts.main')

@section('container')
    <!-- Sale & Revenue Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-sm-6 col-xl-5">
                <div class="bg-light rounded d-flex align-items-center justify-content-start gap-3 p-4">
                    <i class="fa fa-file-alt fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Surat Pernyataan Magang</p>
                        <h6 class="mb-0">{{ $totalPernyataanMagang }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-5">
                <div class="bg-light rounded d-flex align-items-center justify-content-start gap-3 p-4">
                    <i class="fa fa-clock fa-3x text-warning"></i>
                    <div class="ms-3">
                        <p class="mb-2">Surat Belum Selesai</p>
                        <h6 class="mb-0">{{ $totalBelumSelesai }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Sale & Revenue End -->

    <!-- Recent Sales Start -->
    <div class="container-fluid mb-4 pt-4 px-3">
        <div class="bg-light text-center rounded p-4">
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Daftar Surat Pernyataan Magang</h6>
                <a href="/dashboard/mahasiswa/pernyataan-magang/create" class="btn btn-primary">Tambah Surat</a>
            </div>
            <div class="table-responsive">
                @include('dashboard.mahasiswa.pernyataan_magangs.table')
            </div>
        </div>
    </div>
@endsection
