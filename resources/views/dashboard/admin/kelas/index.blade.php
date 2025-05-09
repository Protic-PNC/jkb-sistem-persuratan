@extends('dashboard.admin.layouts.main')

@section('container')
    <!-- Sale & Revenue Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-sm-6 col-xl-5">
                <div class="bg-light rounded d-flex flex-row align-items-center p-4">
                    <i class="fa fa-chalkboard fa-3x text-info me-3"></i>
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
                <span id="swal-success-message" data-message="{{ session('success') }}"></span>
            @endif
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Daftar Kelas</h6>
                <div>
                    <a href="/dashboard/admin/kelas/create" class="btn btn-primary">Tambah Kelas</a>
                    <a href="/dashboard/admin/kelas/import" class="btn btn-info ms-2 text-white">Upload CSV</a>
                    <form action="/dashboard/admin/kelas/reset" method="post" class="d-inline ms-2" id="resetKelasForm">
                        @csrf
                        <button type="button" class="btn btn-danger" onclick="confirmResetKelas()">Reset Kelas</button>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                @include('dashboard.admin.kelas.table')
            </div>
        </div>
    </div>

    <script>
        function confirmResetKelas() {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Tindakan ini akan menghapus semua kelas!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, reset kelas!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('resetKelasForm').submit();
                }
            });
        }
    </script>
@endsection
