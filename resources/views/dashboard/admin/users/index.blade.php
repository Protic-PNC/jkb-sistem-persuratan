@extends('dashboard.admin.layouts.main')

@section('container')
    <!-- Sale & Revenue Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-sm-6 col-xl-5">
                <div class="bg-light rounded d-flex flex-row align-items-center p-4">
                    <i class="fa fa-user-circle fa-3x text-info me-3"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Akun</p>
                        <h6 class="mb-0">{{ $totalUser }}</h6>
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
                <h6 class="mb-0">Daftar Akun</h6>
                <div>
                    <a href="/dashboard/admin/user/create" class="btn btn-primary">Tambah Akun</a>
                    <a href="/dashboard/admin/user/import" class="btn btn-info ms-2 text-white">Upload CSV</a>
                    <form action="/dashboard/admin/user/reset" method="post" class="d-inline ms-2" id="resetUserForm">
                        @csrf
                        <button type="button" class="btn btn-danger" onclick="confirmResetUser()">Reset Akun</button>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                @include('dashboard.admin.users.table')
            </div>
        </div>
    </div>

    <script>
        function confirmResetUser() {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Tindakan ini akan menghapus semua akun kecuali admin!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, reset akun!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('resetUserForm').submit();
                }
            });
        }
    </script>
@endsection
