<!DOCTYPE html>
<html>

<head>
    <title>Surat Peringatan Akademik</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .content {
            padding: 20px;
        }
        .footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 0.9em;
            color: #666;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Surat Peringatan karena Pelanggaran Peraturan Akademik</h1>
        </div>
        
        <div class="content">
            @php
                $recipientName = $recipientName ?? $pelanggaranAkademik->nama_mhs;
            @endphp
            @if ($pelanggaranAkademik->ttd_mahasiswa && 
                 $pelanggaranAkademik->ttd_pelapor && 
                 $pelanggaranAkademik->ttd_dosen_wali && 
                 $pelanggaranAkademik->ttd_ketua_jurusan)
                <p>Halo {{ $recipientName }},</p>
                <p>Surat Peringatan karena Pelanggaran Peraturan Akademik dengan No. Surat: {{ $pelanggaranAkademik->noSurat }} telah selesai dan ditandatangani oleh semua pihak terkait.</p>
            @elseif($pelanggaranAkademik->status_surat == 'ditolak')
                <p>Halo {{ $recipientName }},</p>
                <p>Surat Peringatan karena Pelanggaran Peraturan Akademik dengan No. Surat: {{ $pelanggaranAkademik->noSurat }} telah ditolak.</p>
            @else
                <p>Halo {{ $recipientName }},</p>
                <p>Terdapat Surat Peringatan karena Pelanggaran Akademik yang perlu ditindaklanjuti:</p>
                <ul>
                    <li>No. Surat: {{ $pelanggaranAkademik->noSurat }}</li>
                    <li>Tanggal Surat: {{ $pelanggaranAkademik->tglSurat }}</li>
                    <li>Jenis Peringatan: {{ $pelanggaranAkademik->peringatan }}</li>
                    <li>Pelapor: {{ $pelanggaranAkademik->nama_pelapor }}</li>
                </ul>
                <p>Harap segera diproses pada website berikut:</p>
                <a href="{{ url('http://127.0.0.1:8000') }}" class="button" style="color: #fff;">Login</a>
            @endif
        </div>

        <div class="footer">
            <p>Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
            <p>Terima kasih.</p>
        </div>
    </div>
</body>

</html>
