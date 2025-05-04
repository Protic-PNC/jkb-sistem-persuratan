@extends('dashboard.admin.layouts.main')

@section('container')
    <!-- Sale & Revenue Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-sm-6 col-xl-5">
                <div class="bg-light rounded d-flex flex-row align-items-center p-4">
                    <i class="fa fa-chalkboard-teacher fa-3x text-success me-3"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Kelas</p>
                        <h6 class="mb-0">{{ $totalKelas }}</h6>
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
                <h6 class="mb-0">Daftar Kelas</h6>
                <div>
                    <a href="/dashboard/admin/kelas/create" class="btn btn-primary">Tambah Kelas</a>
                    <a href="/dashboard/admin/kelas/import" class="btn btn-info ms-2 text-white">Upload CSV</a>
                </div>
            </div>

            <div class="table-responsive">
                @include('dashboard.admin.kelas.table')
            </div>
        </div>
    </div>
@endsection
