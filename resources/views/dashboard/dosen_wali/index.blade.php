@extends('dashboard.dosen_wali.layouts.main')

@section('container')
    <!-- Sale & Revenue Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-sm-6 col-xl-5">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-bar fa-3x text-primary me-3"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Surat Peringatan Pelanggaran Peraturan Akademik Kelas {{ $kelas->nama_kelas ?? '...' }}</p>
                        <h6 class="mb-0">{{ $totalPelanggaranAkademikKelas }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-5">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-bar fa-3x text-primary me-3"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Surat Peringatan Pelanggaran Peraturan Akademik Dosen Wali</p>
                        <h6 class="mb-0">{{ $totalPelanggaranAkademik }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-5">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-bar fa-3x text-primary me-3"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Surat Pengunduran Diri Kelas {{ $kelas->nama_kelas ?? '...' }}</p>
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
                <h6 class="mb-0">Daftar Surat Peringatan Pelanggaran Akademik Kelas {{ $kelas->nama_kelas ?? '...' }} Terbaru</h6>
                <a href="/dashboard/dosen-wali/pelanggaran-akademik">Lihat Semua</a>
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
                        @foreach ($pelanggaranKelas as $pelanggaranKls)
                            <tr>
                                <td style="white-space: nowrap; text-align: center;">{{ $pelanggaranKls->noSurat }}</td>
                                <td style="white-space: nowrap; text-align: center;">{{ $pelanggaranKls->nama_mhs }}</td>
                                <td style="white-space: nowrap; text-align: center;">{{ date('d M Y', strtotime($pelanggaranKls->tglSurat)) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="container-fluid mb-4 pt-4 px-4">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Daftar Surat Peringatan Pelanggaran Akademik Dosen Wali Terbaru</h6>
                <a href="/dashboard/dosen-wali/pelanggaran-akademik">Lihat Semua</a>
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
                                <td style="white-space: nowrap; text-align: center;">{{ date('d M Y', strtotime($pelanggaran->tglSurat)) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
