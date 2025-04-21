<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Pengunduran Diri</title>
    <style>
        body {
            margin: 0 auto;
            width: 700px;
            font-family: 'Arial', sans-serif;
            font-size: 14px;
            line-height: 1.5;
        }

        h3 {
            text-align: center;
            margin: 0;
        }

        .line {
            height: 20px;
            border-bottom: 1px dotted black;
            margin: 10px 0;
        }

        .signature-table {
            width: 100%;
            margin-top: 40px;
            border-collapse: collapse;
        }

        .signature-table td {
            text-align: center;
            vertical-align: top;
            padding: 10px;
        }

        .signature-line {
            margin-top: 20px;
            height: 20px;
            border-bottom: 1px dotted black;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }

        .materai {
            font-size: 12px;
            margin-top: 10px;
        }

        .center-text {
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <h3>PERMOHONAN PENGUNDURAN DIRI</h3>
    <br>
    <p>Kepada Yth.<br>
        Direktur Politeknik Negeri Cilacap</p>
    <p>Yang bertanda tangan di bawah ini:</p>
    <table>
        <tr>
            <td width="150">Nama</td>
            <td>: {{ $pengundurans->nama_mhs }}</td>
        </tr>
        <tr>
            <td>NPM</td>
            <td>: {{ $pengundurans->username }}</td>
        </tr>
        <tr>
            <td>Kelas/Semester</td>
            <td>: {{ optional($pengundurans->kelas)->nama_kelas }} / {{ $pengundurans->semester }}</td>
        </tr>
        <tr>
            <td>Jurusan</td>
            <td>: {{ $pengundurans->jurusan }}</td>
        </tr>
        <tr>
            <td>No. Telp/HP</td>
            <td>: {{ $pengundurans->no_telp }}</td>
        </tr>
        <tr>
            <td>Alamat Lengkap</td>
            <td>: {{ $pengundurans->alamat }}</td>
        </tr>
    </table>

    <p>Dengan ini mengajukan permohonan pengunduran diri sebagai mahasiswa Politeknik Negeri Cilacap karena:</p>
    <div class="line">{{ $pengundurans->alasan }}</div>
    <p>Demikian permohonan kami, atas perhatian dan kebijaksanaannya kami ucapkan terima kasih.</p>

    <!-- Bagian tanda tangan -->
    <table class="signature-table">
        <!-- Baris pertama: Orangtua/Wali dan Pemohon -->
        <tr>
            <td>
                <p>Orangtua/Wali</p>
                <div class="materai">Materai Rp. 10.000,-</div>
                <div class="signature-line"></div>
            </td>
            <td>
            </td>
            <td>
                <p>Cilacap, {{ \Carbon\Carbon::parse($pengundurans->tglSurat)->format('d M Y') }}<br>Pemohon</p>
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(storage_path('app/public/' . $pengundurans->ttd_mahasiswa))) }}"
                    alt="TTD Mahasiswa" style="width: 80px;">
                <div class="signature-line"></div>
                {{ $pengundurans->nama_mhs }}
            </td>
        </tr>

        <!-- Baris kedua: Mengetahui -->
        <tr>
            <td>
                <p>Bagian Keuangan</p>
                <br>
                <div class="signature-line"></div>
                <p>NIP: ....................................</p>
            </td>
            <td class="center-text">
                <p><b>Mengetahui</b></p>
            </td>
            <td>
                <p>Bagian Perpustakaan</p>
                <br>
                <div class="signature-line"></div>
                <p>NIP: ....................................</p>
            </td>
        </tr>

        <!-- Baris ketiga: Menyetujui -->
        <tr>
            <td>
                <p>Ketua Jurusan/Prodi Teknik</p>
                <br>
                <div class="signature-line"></div>
                <p>NIP: {{ $pengundurans->username }}</p>
            </td>
            <td class="center-text">
                <p><b>Menyetujui</b></p>
            </td>
            <td>
                <p>Wali Kelas</p>
                <br>
                <div class="signature-line"></div>
                <p>NIP: {{ $pengundurans->username }}</p>
            </td>
        </tr>
    </table>
</body>

</html>
