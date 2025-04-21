@extends('dashboard.dosen_wali.layouts.main')

@section('container')
    <div class="container-fluid pt-4 px-4" style="width: 100%; max-width: 100%; margin: auto;">
        <div class="bg-white rounded p-4" style="font-family: Arial, sans-serif; font-size: 12px;">
            <div class="d-flex justify-content-between mb-3">
                <a href="/dashboard/dosen-wali/pengunduran-diri" class="btn btn-success">
                    <i class="bi bi-arrow-left-square"></i> Kembali
                </a>
                <a href="/dashboard/dosen-wali/pengunduran-diri/{{ $pengundurans->noSurat }}/cetak" class="btn btn-secondary">
                    <i class="bi bi-printer"></i> Cetak
                </a>
            </div>

            <!-- Konten Surat -->
            <div style="margin: 0 auto; width: 700px; font-family: 'Arial', sans-serif; font-size: 14px; line-height: 1.5;">
                <h6 style="text-align: center; margin: 0;">PERMOHONAN PENGUNDURAN DIRI</h6>
                <br>
                <p>Kepada Yth.<br>Direktur Politeknik Negeri Cilacap</p>
                <p>Yang bertanda tangan di bawah ini:</p>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="width: 150px;">Nama</td>
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
                <div style="height: 20px; border-bottom: 1px dotted black; margin: 10px 0;">{{ $pengundurans->alasan }}
                </div>
                <p>Demikian permohonan kami, atas perhatian dan kebijaksanaannya kami ucapkan terima kasih.</p>

                <!-- Bagian tanda tangan -->
                <table style="width: 100%; margin-top: 40px; border-collapse: collapse;">
                    <tr>
                        <td style="text-align: center; vertical-align: top; padding: 10px;">
                            <p>Orangtua/Wali</p>
                            <div style="font-size: 12px; margin-top: 10px;">Materai Rp. 10.000,-</div>
                            <div
                                style="margin-top: 20px; height: 20px; border-bottom: 1px dotted black; width: 80%; margin-left: auto; margin-right: auto;">
                            </div>
                        </td>
                        <td></td>
                        <td style="text-align: center; vertical-align: top; padding: 10px;">
                            <p>Cilacap, {{ \Carbon\Carbon::parse($pengundurans->tglSurat)->format('d M Y') }}<br>Pemohon</p>
                            <img src="{{ asset('storage/' . $pengundurans->ttd_mahasiswa) }}" alt="TTD Mahasiswa"
                                style="width: 80px;">
                            <div
                                style="margin-top: 20px; height: 20px; border-bottom: 1px dotted black; width: 80%; margin-left: auto; margin-right: auto;">
                            </div>
                            {{ $pengundurans->nama_mhs }}
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center; vertical-align: top; padding: 10px;">
                            <p>Bagian Keuangan</p>
                            <br>
                            <div
                                style="margin-top: 20px; height: 20px; border-bottom: 1px dotted black; width: 80%; margin-left: auto; margin-right: auto;">
                            </div>
                            <p>NIP: ....................................</p>
                        </td>
                        <td style="font-weight: bold; text-align: center; vertical-align: top;">
                            <p><b>Mengetahui</b></p>
                        </td>
                        <td style="text-align: center; vertical-align: top; padding: 10px;">
                            <p>Bagian Perpustakaan</p>
                            <br>
                            <div
                                style="margin-top: 20px; height: 20px; border-bottom: 1px dotted black; width: 80%; margin-left: auto; margin-right: auto;">
                            </div>
                            <p>NIP: ....................................</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center; vertical-align: top; padding: 10px;">
                            <p>Ketua Jurusan/Prodi Teknik</p>
                            <br>
                            <div
                                style="margin-top: 20px; height: 20px; border-bottom: 1px dotted black; width: 80%; margin-left: auto; margin-right: auto;">
                            </div>
                            <p>NIP: {{ $pengundurans->username }}</p>
                        </td>
                        <td style="font-weight: bold; text-align: center; vertical-align: top;">
                            <p><b>Menyetujui</b></p>
                        </td>
                        <td style="text-align: center; vertical-align: top; padding: 10px;">
                            <p>Wali Kelas</p>
                            <br>
                            <div
                                style="margin-top: 20px; height: 20px; border-bottom: 1px dotted black; width: 80%; margin-left: auto; margin-right: auto;">
                            </div>
                            <p>NIP: {{ $pengundurans->username }}</p>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
@endsection
