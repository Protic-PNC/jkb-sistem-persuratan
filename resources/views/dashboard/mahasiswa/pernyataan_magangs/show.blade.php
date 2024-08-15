@extends('dashboard.mahasiswa.layouts.main')

@section('container')

<!-- Recent Sales Start -->
<div class="container-fluid pt-4 px-4">
    <div class="bg-light text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <a href="/dashboard/mahasiswa/pernyataan-magang" class="btn btn-success"><i class="bi bi-arrow-left-square"></i> Kembali</a>
            <a href="/dashboard/mahasiswa/pernyataan-magang/{{ $pernyataans->noSurat }}/cetak" class="btn btn-secondary"><i class="bi bi-printer"></i> Cetak</a>
        </div>
        <center style="margin-top: 50px;">
            <table width="545">
                <tr>
                    <td style="font-family: 'Times New Roman', Times, serif; font-size: 18px; text-align: center; font-weight: bold">
                        <u>SURAT PERNYATAAN</u>
                    </td>
                </tr>
            </table>
            <br /><br /><br />
            <table width="545">
                <tr>
                    <td>Yang bertanda tangan di bawah ini :</td>
                </tr>
            </table>
            <br /><br />
            <table width="545">
                <tr>
                    <td width="200">Nama</td>
                    <td width="10">:</td>
                    <td width="335">{{ $pernyataans->nama_ortu }}</td>
                </tr>
                <tr>
                    <td width="200">Alamat</td>
                    <td width="10">:</td>
                    <td width="335">{{ $pernyataans->alamat }}</td>
                </tr>
                <tr>
                    <td width="200">No. Telp</td>
                    <td width="10">:</td>
                    <td width="335">{{ $pernyataans->no_telp }}</td>
                </tr>
            </table>
            <br /><br />
            <table width="545">
                <tr>
                    <td>Orang tua/Wali dari :</td>
                </tr>
            </table>
            <br /><br />
            <table width="545">
                <tr>
                    <td width="200">Nama</td>
                    <td width="10">:</td>
                    <td width="335">{{ $pernyataans->nama_mhs }}</td>
                </tr>
                <tr>
                    <td width="200">NPM</td>
                    <td width="10">:</td>
                    <td width="335">{{ $pernyataans->npm }}</td>
                </tr>
                <tr>
                    <td width="200">Jurusan</td>
                    <td width="10">:</td>
                    <td width="335">{{ $pernyataans->jurusan }}</td>
                </tr>
                <tr>
                    <td width="200">Perguruan Tinggi</td>
                    <td width="10">:</td>
                    <td width="335">{{ $pernyataans->perguruan_tinggi }}</td>
                </tr>
            </table>
            <br /><br />
            <table width="545">
                <tr>
                    <td>
                        Dengan ini menyatakan bahwa :
                        <ol>
                            <li>
                                Menyetujui pelaksanaan dan keikutsertaan anak kami dalam program magang industri dari awal hingga berakhir program magang industri Politeknik Negeri Cilacap.
                            </li>
                            <li>
                                Bersedia mengikuti segala peraturan dan ketentuan magang industri yang berlaku di Politeknik Negeri Cilacap dan di tempat magang industri.
                            </li>
                        </ol>
                    </td>
                </tr>
            </table>
            <br /><br />
            <table width="545">
                <tr>
                    <td>
                        Demikian surat pernyataan ini saya buat, untuk dapat dipergunakan sebagaimana mestinya.
                    </td>
                </tr>
            </table>
            <br /><br /><br />
            <table>
                <tr>
                  <td width="300"></td>
                  <td class="text-center">
                    Cilacap, {{ date('d M Y', strtotime($pernyataans->tglSurat)) }}<br />
                    Orangtua/Wali Mahasiswa,
                  </td>
                </tr>
                <tr>
                  <td width="300"></td>
                  <td class="text-center mt-5">
                  </br>
                  </br>
                    <i>Materai Rp. 10.000,-</i><br />
                  </br>
                  </br>
                    ...................................................<br />
                  </td>
                </tr>
            </table>
              
        </center>
    </div>
</div>
<!-- Recent Sales End -->

@endsection
