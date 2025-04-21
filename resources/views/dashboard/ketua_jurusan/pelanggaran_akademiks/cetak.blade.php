<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Peringatan</title>
    <style>
        /* Center the body content */
        body {
            margin: 0 auto;
            width: 100%;
            font-family: 'Arial', sans-serif;
            font-size: 12px;
        }

        /* Center text alignment */
        .text-center {
            text-align: center;
        }

        /* Add bottom space */
        .mb-3 {
            margin-bottom: 20px;
        }

        /* Add top space */
        .mt-5 {
            margin-top: 40px;
        }

        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 5px;
        }

        .border {
            border: 1px solid black;
        }

        .text-normal {
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="text-center">
        <!-- Header Section -->
        <table>
            <tr>
                <td width="15%">
                    <img src="img/pnc_logo.png" alt="Logo" style="width: 80px;">
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
        <table style="line-height: 1.4; margin-bottom: 15px;">
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
                        <span style="font-weight: bold; text-decoration: none;">LISAN</span> &nbsp;&nbsp;&nbsp;&nbsp;
                        <span style="text-decoration: line-through; font-weight: bold;">TERTULIS</span>
                    @else
                        <span style="font-weight: bold; text-decoration: none;">LISAN</span> &nbsp;&nbsp;&nbsp;&nbsp;
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
        <div class="border" style="margin-bottom: 25px;">
            <!-- Warning Count Section -->
            <table class="text-normal">
                <tr>
                    <td style="padding: 3px;">
                        Peringatan ini diberikan untuk yang ke :
                    </td>
                    <td style="text-align: left; padding-right: 470px;">
                        {{ $pelanggarans->jumlah_peringatan }}
                    </td>
                </tr>
            </table>
            <!-- TTDs Section -->
            <table class="border text-center" style="margin-bottom: 0;">
                <tr>
                    <td class="border" style="padding: 15px; white-space: nowrap;" width="25%">Mahasiswa
                        ybs.<br><br><br>
                        @if ($pelanggarans->ttd_mahasiswa)
                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(storage_path('app/public/' . $pelanggarans->ttd_mahasiswa))) }}"
                                alt="TTD Mahasiswa" style="width: 80px;">
                        @endif
                        <br><br><br>
                        {{ $pelanggarans->nama_mhs }}
                    </td>
                    <td class="border" style="padding: 15px; white-space: nowrap;" width="25%">Yang
                        Melaporkan<br><br><br>
                        @if ($pelanggarans->ttd_pelapor)
                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(storage_path('app/public/' . $pelanggarans->ttd_pelapor))) }}"
                                alt="TTD Pelapor" style="width: 80px;">
                        @endif
                        <br><br><br>
                        {{ $pelanggarans->nama_pelapor }}
                    </td>
                    <td class="border" style="padding: 15px; white-space: nowrap;" width="25%">Dosen Wali<br><br><br>
                        @if ($pelanggarans->ttd_dosen_wali)
                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(storage_path('app/public/' . $pelanggarans->ttd_dosen_wali))) }}"
                                alt="TTD Dosen Wali" style="width: 80px;">
                        @endif
                        <br><br><br>
                        {{ $pelanggarans->nama_dosen_wali }}
                    </td>
                    <td class="border" style="padding: 15px; white-space: nowrap;" width="25%">Ketua
                        Jurusan<br><br><br>
                        @if ($pelanggarans->ttd_ketua_jurusan)
                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(storage_path('app/public/' . $pelanggarans->ttd_ketua_jurusan))) }}"
                                alt="TTD Ketua Jurusan" style="width: 80px;">
                        @endif
                        <br><br><br>
                        {{ $pelanggarans->nama_ketua_jurusan }}
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>
