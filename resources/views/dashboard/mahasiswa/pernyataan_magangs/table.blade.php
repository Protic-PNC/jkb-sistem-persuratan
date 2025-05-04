<table id="mahasiswa-table" class="table text-start align-middle table-bordered table-hover mb-0">
    <thead>
        <tr class="text-dark">
            <th scope="col" style="white-space: nowrap; text-align: center;">No. Surat</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">Nama Mahasiswa</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">NPM</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">Jurusan</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">Tanggal Surat</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">Berkas</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">Status</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">Action</th>
        </tr>
    </thead>
    <tbody id="results-body">
        @foreach ($pernyataans as $pernyataan)
            <tr>
                <td style="white-space: nowrap; text-align: center;">{{ $pernyataan->noSurat }}</td>
                <td style="white-space: nowrap; text-align: center;">{{ $pernyataan->nama_mhs }}</td>
                <td style="white-space: nowrap; text-align: center;">{{ $pernyataan->username }}</td>
                <td style="white-space: nowrap; text-align: center;">{{ $pernyataan->jurusan }}</td>
                <td style="white-space: nowrap; text-align: center;">
                    {{ date('d M Y', strtotime($pernyataan->tglSurat)) }}
                </td>
                <td style="text-align: center;">
                    @if ($pernyataan->file_pdf)
                        <a href="{{ asset('storage/' . $pernyataan->file_pdf) }}" target="_blank">Lihat</a>
                    @else
                        Belum Upload
                    @endif
                </td>
                <td style="white-space: nowrap; text-align: center;">
                    @if ($pernyataan->status === 'approved')
                        <span class="badge bg-success">Disetujui</span>
                    @elseif ($pernyataan->status === 'rejected')
                        <span class="badge bg-danger">Ditolak</span>
                    @else
                        <span class="badge bg-secondary">Belum Selesai</span>
                    @endif
                </td>
                <td>
                    <div class="d-flex justify-content-start">
                        <a class="btn btn-sm btn-primary me-2"
                            href="/dashboard/mahasiswa/pernyataan-magang/{{ $pernyataan->noSurat }}">Detail</a>
                        <a class="btn btn-sm btn-warning me-2"
                            href="/dashboard/mahasiswa/pernyataan-magang/{{ $pernyataan->noSurat }}/edit">Ubah</a>
                        <form action="/dashboard/mahasiswa/pernyataan-magang/{{ $pernyataan->noSurat }}" method="post"
                            class="d-inline" id="delete-form-{{ $pernyataan->noSurat }}">
                            @method('delete')
                            @csrf
                            <button type="button" class="btn btn-sm btn-danger me-2 border-0"
                                onclick="confirmDelete('{{ $pernyataan->noSurat }}')">Hapus</button>
                        </form>
                        <a class="btn btn-sm btn-info text-white"
                            href="/dashboard/mahasiswa/pernyataan-magang/{{ $pernyataan->noSurat }}/upload">Upload
                            PDF</a>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
