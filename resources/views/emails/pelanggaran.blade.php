<!DOCTYPE html>
<html>

<head>
    <title>Surat Peringatan Akademik</title>
</head>

<body>
    <h1>Surat Peringatan karena Pelanggaran Peraturan Akademik</h1>

    @if (
        $pelanggaranAkademik->ttd_mahasiswa &&
            $pelanggaranAkademik->ttd_pelapor &&
            $pelanggaranAkademik->ttd_dosen_wali &&
            $pelanggaranAkademik->ttd_ketua_jurusan)
        <p>Halo {{ $pelanggaranAkademik->nama_mhs }}, Surat Peringatan karena Pelanggaran Peraturan Akademik dengan No.
            Surat: {{ $pelanggaranAkademik->noSurat }} telah selesai.</p>
    @elseif($pelanggaranAkademik->status_surat == 'ditolak')
        <p>Halo {{ $pelanggaranAkademik->nama_mhs }}, Surat Permohonan Pengunduran Diri dengan No. Surat:
            {{ $pelanggaranAkademik->noSurat }} ditolak.</p>
    @else
        <p>Halo {{ $pelanggaranAkademik->nama_mhs }}, terdapat Surat Peringatan karena Pelanggaran Akademik No. Surat:
            {{ $pelanggaranAkademik->noSurat }} untuk Anda. Harap segera untuk diproses pada website berikut <a
                href="http://127.0.0.1:8000">Klik di sini</a>.</p>
    @endif

    <p>Terima kasih.</p>
</body>

</html>
