<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Pernyataan Magang</title>
    <style>
        /* Center the body content */
        body {
            margin: 0 auto;
            width: 600px;
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
    </style>
</head>

<body>
    <div class="text-center">
        <h3 style="margin: 0;"><u>SURAT PERNYATAAN</u></h3>
    </div>

    <br /><br />

    <p class="text-normal">Yang bertanda tangan di bawah ini :</p>

    <table class="text-normal">
        <tr>
            <td width="120">Nama</td>
            <td width="10">:</td>
            <td>{{ $pernyataans->nama_ortu }}</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>:</td>
            <td>{{ $pernyataans->alamat }}</td>
        </tr>
        <tr>
            <td>No. Telp</td>
            <td>:</td>
            <td>{{ $pernyataans->no_telp }}</td>
        </tr>
    </table>

    <br />

    <p class="text-normal">Orang tua/Wali dari :</p>

    <table class="text-normal">
        <tr>
            <td width="120">Nama</td>
            <td width="10">:</td>
            <td>{{ $pernyataans->nama_mhs }}</td>
        </tr>
        <tr>
            <td>NPM</td>
            <td>:</td>
            <td>{{ $pernyataans->username }}</td>
        </tr>
        <tr>
            <td>Jurusan</td>
            <td>:</td>
            <td>{{ $pernyataans->jurusan }}</td>
        </tr>
        <tr>
            <td>Perguruan Tinggi</td>
            <td>:</td>
            <td>{{ $pernyataans->perguruan_tinggi }}</td>
        </tr>
    </table>

    <br />

    <p class="text-normal">Dengan ini menyatakan bahwa :</p>
    <ol class="text-normal">
        <li>
            Menyetujui pelaksanaan dan keikutsertaan anak kami dalam program magang industri dari awal hingga berakhir
            program magang industri Politeknik Negeri Cilacap.
        </li>
        <li>
            Bersedia mengikuti segala peraturan dan ketentuan magang industri yang berlaku di Politeknik Negeri Cilacap
            dan di tempat magang industri.
        </li>
    </ol>

    <br />

    <p class="text-normal">
        Demikian surat pernyataan ini saya buat, untuk dapat dipergunakan sebagaimana mestinya.
    </p>

    <br /><br />

    <table class="text-normal">
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
</body>

</html>