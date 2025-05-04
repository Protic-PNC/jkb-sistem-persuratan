@extends('dashboard.admin.layouts.main')

@section('container')
    <!-- Sale & Revenue Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-sm-6 col-xl-5">
                <div class="bg-light rounded d-flex flex-row align-items-center p-4">
                    <i class="fa fa-file-alt fa-3x text-primary me-3"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Surat Pernyataan Magang</p>
                        <h6 class="mb-0">{{ $totalPernyataan }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-5">
                <div class="bg-light rounded d-flex flex-row align-items-center p-4">
                    <i class="fa fa-file-alt fa-3x text-primary me-3"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Surat Pengunduran Diri</p>
                        <h6 class="mb-0">{{ $totalPengunduran }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-5">
                <div class="bg-light rounded d-flex flex-row align-items-center p-4">
                    <i class="fa fa-file-alt fa-3x text-primary me-3"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Surat Peringatan Pelanggaran Peraturan Akademik</p>
                        <h6 class="mb-0">{{ $totalPelanggaran }}</h6>
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
                <a href="/dashboard/admin/pernyataan-magang">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0">
                    <thead>
                        <tr class="text-dark">
                            <th scope="col" style="white-space: nowrap; text-align: center;">No Surat</th>
                            <th scope="col" style="white-space: nowrap; text-align: center;">Nama Mahasiswa</th>
                            <th scope="col" style="white-space: nowrap; text-align: center;">Tanggal Surat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pernyataans as $pernyataan)
                            <tr>
                                <td style="white-space: nowrap; text-align: center;">{{ $pernyataan->noSurat }}</td>
                                <td style="white-space: nowrap; text-align: center;">{{ $pernyataan->nama_mhs }}</td>
                                <td style="white-space: nowrap; text-align: center;">
                                    {{ date('d M Y', strtotime($pernyataan->tglSurat)) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Recent Sales End -->
    <!-- Recent Sales Start -->
    <div class="container-fluid mb-4 pt-4 px-4">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Daftar Surat Peringatan karena Pelanggaran Akademik Terbaru</h6>
                <a href="/dashboard/admin/pelanggaran-akademik">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0">
                    <thead>
                        <tr class="text-dark">
                            <th scope="col" style="white-space: nowrap; text-align: center;">No Surat</th>
                            <th scope="col" style="white-space: nowrap; text-align: center;">Nama Mahasiswa</th>
                            <th scope="col" style="white-space: nowrap; text-align: center;">Tanggal Surat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pelanggarans as $pelanggaran)
                            <tr>
                                <td style="white-space: nowrap; text-align: center;">{{ $pelanggaran->noSurat }}</td>
                                <td style="white-space: nowrap; text-align: center;">{{ $pelanggaran->nama_mhs }}</td>
                                <td style="white-space: nowrap; text-align: center;">
                                    {{ date('d M Y', strtotime($pelanggaran->tglSurat)) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Recent Sales Start -->
    <div class="container-fluid mb-4 pt-4 px-4">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Daftar Surat Permohonan Pengunduran Diri Terbaru</h6>
                <a href="/dashboard/admin/pengunduran-diri">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0">
                    <thead>
                        <tr class="text-dark">
                            <th scope="col" style="white-space: nowrap; text-align: center;">No Surat</th>
                            <th scope="col" style="white-space: nowrap; text-align: center;">Nama Mahasiswa</th>
                            <th scope="col" style="white-space: nowrap; text-align: center;">Tanggal Surat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pengundurans as $pengunduran)
                            <tr>
                                <td style="white-space: nowrap; text-align: center;">{{ $pengunduran->noSurat }}</td>
                                <td style="white-space: nowrap; text-align: center;">{{ $pengunduran->nama_mhs }}</td>
                                <td style="white-space: nowrap; text-align: center;">
                                    {{ date('d M Y', strtotime($pengunduran->tglSurat)) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
