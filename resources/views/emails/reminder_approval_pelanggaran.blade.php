<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Pengingat Persetujuan</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #212529;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #2c3e50;
            font-size: 24px;
            font-weight: 600;
        }

        p {
            font-size: 16px;
            line-height: 1.6;
        }

        ul {
            list-style-type: none;
            padding-left: 0;
        }

        li {
            font-size: 16px;
            margin-bottom: 8px;
        }

        .highlight {
            font-weight: bold;
            color: #007bff;
        }

        .btn {
            display: inline-block;
            margin-top: 20px;
            background-color: #007bff;
            color: #ffffff !important;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #6c757d;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>
            Yth.
            {{ $opsi['nama'] ?? 'Bapak/Ibu' }},
        </h2>

        <p>Ini adalah pengingat bahwa Anda sebagai <span class="highlight">{{ $opsi['role'] ?? 'pengguna' }}</span> perlu mengambil keputusan (menyetujui atau menolak) terhadap Surat Peringatan karena Pelanggaran Peraturan Akademik
            mahasiswa berikut:</p>

        <ul>
            <li><span class="highlight">Nama Mahasiswa:</span> {{ $pelanggaran->nama_mhs }}</li>
            <li><span class="highlight">Nama Pelapor:</span> {{ $pelanggaran->nama_pelapor }}</li>
            <li><span class="highlight">Jenis Peringatan:</span> {{ $pelanggaran->peringatan }}</li>
            <li><span class="highlight">Tanggal Surat:</span> {{ $pelanggaran->tglSurat }}</li>
        </ul>

        <p>Silakan segera memberikan keputusan Anda untuk memproses surat pelanggaran ini.</p>

        <a href="{{ url('http://127.0.0.1:8000') }}" class="btn">Login ke Sistem</a>

        <p class="footer">Terima kasih, Tim Admin</p>
    </div>
</body>

</html> 