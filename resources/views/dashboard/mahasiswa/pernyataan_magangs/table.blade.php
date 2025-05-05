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
            <th scope="col" style="white-space: nowrap; text-align: center;">Alasan</th>
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
                        <span class="badge bg-warning">Diproses</span>
                    @endif
                </td>
                <td style="max-width: 250px; overflow-x: auto;">
                    <div class="d-flex justify-content-start" style="min-width: max-content; gap: 0.5rem;">
                        <a class="btn btn-sm btn-primary"
                            href="/dashboard/mahasiswa/pernyataan-magang/{{ $pernyataan->noSurat }}">Detail</a>
                        <a class="btn btn-sm btn-warning"
                            href="/dashboard/mahasiswa/pernyataan-magang/{{ $pernyataan->noSurat }}/edit">Ubah</a>
                        <form action="/dashboard/mahasiswa/pernyataan-magang/{{ $pernyataan->noSurat }}" method="post"
                            class="d-inline">
                            @method('delete')
                            @csrf
                            <button type="button" class="btn btn-sm btn-danger border-0"
                                onclick="confirmDelete('{{ $pernyataan->noSurat }}')">Hapus</button>
                        </form>
                        <a class="btn btn-sm btn-info text-white"
                            href="/dashboard/mahasiswa/pernyataan-magang/{{ $pernyataan->noSurat }}/upload">Upload
                            PDF</a>
                    </div>
                </td>
                <td style="text-align: center;">
                    @if ($pernyataan->status === 'rejected' && $pernyataan->alasan)
                        <button class="btn btn-sm btn-outline-danger"
                            onclick="showAlasanMagang(`{!! addslashes($pernyataan->alasan) !!}`)"
                            style="white-space: nowrap; padding: 5px 15px;">
                            Lihat Alasan
                        </button>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
