@extends('dashboard.bagian_perpustakaan.layouts.main')

@section('container')
    <!-- Sale & Revenue Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-sm-6 col-xl-5">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-bar fa-3x text-primary me-3"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Surat Pengunduran Diri</p>
                        {{-- <h6 class="mb-0">{{ $total }}</h6> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Sale & Revenue End -->
    <div class="container-fluid mb-4 pt-4 px-4">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Daftar Surat Permohonan Pengunduran Diri Terbaru</h6>
                <a href="/dashboard/bagian-perpustakaan/pengunduran-diri">Lihat Semua</a>
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
                                <td style="white-space: nowrap; text-align: center;">{{ date('d M Y', strtotime($pengunduran->tglSurat)) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
