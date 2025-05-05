<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Status Surat Pernyataan Magang</title>
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

        .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo img {
            max-height: 80px;
        }

        h2 {
            color: #2c3e50;
        }

        blockquote {
            background: #f0f0f0;
            padding: 10px 15px;
            border-left: 5px solid #dc3545;
            margin: 15px 0;
            font-style: italic;
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

        .status {
            font-size: 16px;
            font-weight: bold;
            color: {{ $status == 'approved' ? '#28a745' : '#dc3545' }};
        }
    </style>
</head>

<body>
    <div class="container">

        <h2>Halo {{ $pernyataan->nama_mhs }},</h2>

        <p>
            Surat pernyataan magang Anda telah
            <span class="status">{{ $status == 'approved' ? 'disetujui' : 'ditolak' }}</span>.
        </p>

        @if ($status == 'rejected')
            <p><strong>Alasan Penolakan:</strong></p>
            @php
                $alasanList = explode("\n", $pernyataan->alasan);
            @endphp
            <blockquote>
                <ul style="padding-left: 1.2em; margin: 0;">
                    @foreach ($alasanList as $alasan)
                        @if (trim($alasan) !== '')
                            <li>{{ trim($alasan) }}</li>
                        @endif
                    @endforeach
                </ul>
            </blockquote>

        @endif

        <p>Silakan login ke sistem persuratan mahasiswa untuk melihat detailnya melalui tautan di bawah ini:</p>

        <a href="{{ url('/login') }}" class="btn">Login ke Sistem</a>
    </div>
</body>

</html>
