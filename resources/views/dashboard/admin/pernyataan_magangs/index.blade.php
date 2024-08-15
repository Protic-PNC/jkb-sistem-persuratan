@extends('dashboard.admin.layouts.main')

@section('container')
<!-- Sale & Revenue Start -->
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-6 col-xl-5">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-chart-line fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2">Total Surat Pernyataan Magang</p>
                    <h6 class="mb-0">{{ $totalPernyataanMagang }}</h6>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Sale & Revenue End -->

<!-- Recent Sales Start -->
<div class="container-fluid pt-4 px-4">
    <div class="bg-light text-center rounded p-4">
        @if(session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">Daftar Surat Pernyataan Magang</h6>
            <a href="/dashboard/admin/pernyataan-magang/create" class="btn btn-primary">Tambah Surat</a>
        </div>

        <!-- Search Form -->
        <form id="search-form" method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" id="search-input" class="form-control" placeholder="Cari berdasarkan No. Surat, Nama, NPM, Jurusan, atau Tanggal" value="{{ request('search') }}">
            </div>
        </form>

        <div class="table-responsive" id="search-results">
            @include('dashboard.admin.pernyataan_magangs.table')
        </div>
    </div>
</div>

<script>
    document.getElementById('search-input').addEventListener('input', function() {
        let searchQuery = this.value;
        let xhr = new XMLHttpRequest();
        xhr.open('GET', `/dashboard/admin/pernyataan-magang?search=${searchQuery}`, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                document.getElementById('search-results').innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    });
</script>
@endsection
