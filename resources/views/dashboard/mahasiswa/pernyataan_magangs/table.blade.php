<table class="table text-start align-middle table-bordered table-hover mb-0">
    <thead>
        <tr class="text-dark">
            <th scope="col">No. Surat</th>
            <th scope="col">Nama Mahasiswa</th>
            <th scope="col">NPM</th>
            <th scope="col">Jurusan</th>
            <th scope="col">Tanggal Surat</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($pernyataans as $pernyataan)
        <tr>
            <td>{{ $pernyataan->noSurat }}</td>
            <td>{{ $pernyataan->nama_mhs }}</td>
            <td>{{ $pernyataan->npm }}</td>
            <td>{{ $pernyataan->jurusan }}</td>
            <td>{{ date('d M Y', strtotime($pernyataan->tglSurat)) }}</td>
            <td>
                <a class="btn btn-sm btn-primary" href="/dashboard/mahasiswa/pernyataan-magang/{{ $pernyataan->noSurat }}">Detail</a>
                <a class="btn btn-sm btn-warning" href="/dashboard/mahasiswa/pernyataan-magang/{{ $pernyataan->noSurat }}/edit">Edit</a>
                <form action="/dashboard/mahasiswa/pernyataan-magang/{{ $pernyataan->noSurat }}" method="post" class="d-inline">
                    @method('delete')
                    @csrf 
                    <button class="btn btn-sm btn-danger border-0" onclick="return confirm('Klik Oke Untuk Menghapus')">Hapus</button>
                </form>
                <a class="btn btn-sm btn-success" href="/dashboard/mahasiswa/pernyataan-magang/{{ $pernyataan->noSurat }}/cetak">Cetak</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="d-flex mt-3">
    {{ $pernyataans->links() }}
</div>
