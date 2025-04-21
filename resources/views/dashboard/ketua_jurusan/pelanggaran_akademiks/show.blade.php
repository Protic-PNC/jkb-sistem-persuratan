@extends('dashboard.ketua_jurusan.layouts.main')

@section('container')
    <div class="container-fluid pt-4 px-4" style="width: 100%; max-width: 100%; margin: auto;">
        <div class="bg-white rounded p-4" style="font-family: Arial, sans-serif; font-size: 12px;">
            <div class="d-flex justify-content-between mb-3">
                <a href="/dashboard/ketua-jurusan/pelanggaran-akademik" class="btn btn-success">
                    <i class="bi bi-arrow-left-square"></i> Kembali
                </a>
                <a href="/dashboard/ketua-jurusan/pelanggaran-akademik/{{ $pelanggarans->noSurat }}/cetak" class="btn btn-secondary">
                    <i class="bi bi-printer"></i> Cetak
                </a>
            </div>
            <center>
                <!-- Header Section -->
                <table width="100%" style="margin-bottom: 20px;">
                    <tr>
                        <td width="15%">
                            <img src="{{ asset('img/pnc_logo.png') }}" alt="Logo" style="width: 80px;">
                        </td>
                        <td style="text-align: center;">
                            <span style="font-size: 16px; font-weight: bold;">POLITEKNIK NEGERI CILACAP</span><br>
                            <span style="font-size: 14px; font-weight: bold;">PERINGATAN KARENA PELANGGARAN PERATURAN
                                AKADEMIK</span><br>
                            <span style="font-size: 12px;">FM. PAKPM.02-R.0</span>
                        </td>
                        <td width="15%"></td>
                    </tr>
                </table>
                </br>
                <!-- Main Content Section -->
                <table width="100%" style="line-height: 1.4; margin-bottom: 15px;">
                    <tr>
                        <td width="15%">Kepada</td>
                        <td width="2%">:</td>
                        <td style="white-space: nowrap; padding-right: 30px;">{{ $pelanggarans->nama_mhs }}</td>
                        <td width="10%">NPM</td>
                        <td width="2%">:</td>
                        <td>{{ $pelanggarans->username }}</td>
                    </tr>
                    <tr>
                        <td>Smtr/Kelas</td>
                        <td>:</td>
                        <td>{{ $pelanggarans->semester }} / {{ optional($pelanggarans->kelas)->nama_kelas }}</td>
                    </tr>
                    <tr>
                        <td style="white-space: nowrap;">Dengan ini diberikan peringatan</td>
                        <td>:</td>
                        <td colspan="4">
                            @if ($pelanggarans->peringatan == 'lisan')
                                <span style="text-decoration: line-through; font-weight: bold;">LISAN</span>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <span style="font-weight: bold; text-decoration: none;">TERTULIS</span>
                            @elseif($pelanggarans->peringatan == 'tertulis')
                                <span style="font-weight: bold; text-decoration: none;">LISAN</span>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <span style="text-decoration: line-through; font-weight: bold;">TERTULIS</span>
                            @else
                                <span style="font-weight: bold; text-decoration: none;">LISAN</span>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <span style="font-weight: bold; text-decoration: none;">TERTULIS</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Karena pada hari</td>
                        <td>:</td>
                        <td>{{ $pelanggarans->hari }}</td>
                        <td>Tanggal</td>
                        <td>:</td>
                        <td>{{ \Carbon\Carbon::parse($pelanggarans->tglSurat)->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td colspan="6" style="padding-left: 1px; text-align: left;">
                            Telah melakukan pelanggaran Peraturan Akademik tersebut di bawah ini :
                        </td>
                    </tr>
                    <tr>
                        <td>Pasal</td>
                        <td>:</td>
                        <td>{{ $pelanggarans->pasal }}</td>
                        <td>Yaitu</td>
                        <td>:</td>
                        <td>{{ $pelanggarans->isi_pasal }}</td>
                    </tr>
                </table>
                <!-- Wrapper Section -->
                <div style="border: 1px solid black; margin-bottom: 25px;">

                    <!-- Warning Count Section -->
                    <table width="100%" style="border-collapse: collapse; margin-bottom: 0;">
                        <tr>
                            <td style="padding: 3px;">
                                Peringatan ini diberikan untuk yang ke :
                            </td>
                            <td style="text-align: left; padding-right: 650px;">
                                {{ $pelanggarans->jumlah_peringatan }}
                            </td>
                        </tr>
                    </table>

                    <!-- TTDs Section -->
                    <table width="100%" style="text-align: center; border-collapse: collapse; margin-bottom: 0;">
                        <tr>
                            <td style="border: 1px solid black; padding: 15px; border-width: 1px; white-space: nowrap;"
                                width="25%">Mahasiswa ybs.<br><br><br>
                                <img src="{{ asset('storage/' . $pelanggarans->ttd_mahasiswa) }}" alt="TTD Mahasiswa"
                                    style="width: 80px;">
                                <br><br><br>
                                {{ $pelanggarans->nama_mhs }}
                            </td>
                            <td style="border: 1px solid black; padding: 15px; border-width: 1px; white-space: nowrap;"
                                width="25%">Yang Melaporkan<br><br><br>
                                <img src="{{ asset('storage/' . $pelanggarans->ttd_pelapor) }}" alt="TTD Pelapor"
                                    style="width: 80px;">
                                <br><br><br>
                                {{ $pelanggarans->nama_pelapor }}
                            </td>
                            <td style="border: 1px solid black; padding: 15px; border-width: 1px; white-space: nowrap;"
                                width="25%">Dosen Wali<br><br><br>
                                <img src="{{ asset('storage/' . $pelanggarans->ttd_dosen_wali) }}" alt="TTD Dosen Wali"
                                    style="width: 80px;">
                                <br><br><br>
                                {{ $pelanggarans->nama_dosen_wali }}
                            </td>
                            <td style="border: 1px solid black; padding: 15px; border-width: 1px; white-space: nowrap;"
                                width="25%">Ketua Jurusan<br><br><br>
                                <img src="{{ asset('storage/' . $pelanggarans->ttd_ketua_jurusan) }}"
                                    alt="TTD Ketua Jurusan" style="width: 80px;">
                                <br><br><br>
                                {{ $pelanggarans->nama_ketua_jurusan }}
                            </td>
                        </tr>
                    </table>
                </div>
            </center>
        </div>
    </div>
@endsection
