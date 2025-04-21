<!DOCTYPE html>
<html>

<head>
    <title>Surat Permohonan Pengunduran Diri</title>
</head>

<body>
    <h1>Surat Permohonan Pengunduran Diri</h1>

    @if ($pengunduranDiri->ttd_mahasiswa && $pengunduranDiri->ttd_dosen_wali && $pengunduranDiri->ttd_ketua_jurusan)
        <p>Halo {{ $pengunduranDiri->nama_mhs }}, Surat Permohonan Pengunduran Diri dengan No. Surat:
            {{ $pengunduranDiri->noSurat }} telah selesai.</p>
    @elseif($pengunduranDiri->status_surat == 'ditolak')
        <p>Halo {{ $pengunduranDiri->nama_mhs }}, Surat Permohonan Pengunduran Diri dengan No. Surat:
            {{ $pengunduranDiri->noSurat }} ditolak.</p>
    @else
        <p>Halo {{ $pengunduranDiri->nama_mhs }}, terdapat Surat Permohonan Pengunduran Diri dengan No. Surat:
            {{ $pengunduranDiri->noSurat }} untuk Anda. Harap segera untuk diproses pada website berikut <a
                href="http://127.0.0.1:8000">Klik di sini</a>.</p>
    @endif

    <p>Terima kasih.</p>
</body>

</html>
