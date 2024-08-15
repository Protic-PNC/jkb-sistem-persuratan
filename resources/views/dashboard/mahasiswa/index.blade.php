@extends('dashboard.mahasiswa.layouts.main')

@section('container')
<!-- Sale & Revenue Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-sm-6 col-xl-5">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-line fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Surat Pernyataan Magang</p>
                        <h6 class="mb-0">{{ $totalPernyataan }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-5">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-bar fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Surat Pengunduran Diri</p>
                        {{-- <h6 class="mb-0">{{ $total }}</h6> --}}
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-5">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-bar fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Surat Peringatan Pelanggaran Peraturan Akademik</p>
                        {{-- <h6 class="mb-0">{{ $total }}</h6> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Sale & Revenue End -->

    <!-- Recent Sales Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Daftar Surat Pernyataan Magang Terbaru</h6>
                <a href="/dashboard/mahasiswa/pernyataan-magang">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0">
                    <thead>
                        <tr class="text-dark">
                            <th scope="col">No Surat</th>
                            <th scope="col">Nama Mahasiswa</th>
                            <th scope="col">Tanggal Surat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pernyataans as $pernyataan)
                        <tr>
                            <td>{{ $pernyataan->noSurat }}</td>
                            <td>{{ $pernyataan->nama_mhs }}</td>
                            <td>{{ date('d M Y', strtotime($pernyataan->tglSurat)); }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Recent Sales End -->

    <!-- Recent Sales Start -->
    {{-- <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Daftar Surat Keterangan Usaha Terbaru</h6>
                <a href="/dashboard/usaha">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0">
                    <thead>
                        <tr class="text-dark">
                            <th scope="col">No Surat</th>
                            <th scope="col">Nama Warga</th>
                            <th scope="col">Tanggal Surat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($usahas as $usaha)
                        <tr>
                            <td>{{ $usaha->noSurat }}</td>
                            <td>{{ $usaha->nama }}</td>
                            <td>{{ date('d M Y', strtotime($usaha->tglSurat)); }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div> --}}
    <!-- Recent Sales End -->
@endsection