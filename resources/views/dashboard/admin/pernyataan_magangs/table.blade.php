<table id="admin-table" class="table text-start align-middle table-bordered table-hover mb-0">
    <thead>
        <tr class="text-dark">
            <th style="text-align: center;">No. Surat</th>
            <th style="text-align: center;">Nama Mahasiswa</th>
            <th style="text-align: center;">NPM</th>
            <th style="text-align: center;">Jurusan</th>
            <th style="text-align: center;">Tanggal Surat</th>
            <th style="text-align: center;">Berkas</th>
            <th style="text-align: center;">Status</th>
            <th style="text-align: center;">Action</th>
        </tr>
    </thead>
    <tbody id="results-body">
        @foreach ($pernyataans as $pernyataan)
            <tr>
                <td style="text-align: center;">{{ $pernyataan->noSurat }}</td>
                <td style="text-align: center;">{{ $pernyataan->nama_mhs }}</td>
                <td style="text-align: center;">{{ $pernyataan->username }}</td>
                <td style="text-align: center;">{{ $pernyataan->jurusan }}</td>
                <td style="text-align: center;">{{ date('d M Y', strtotime($pernyataan->tglSurat)) }}</td>
                <td style="text-align: center;">
                    @if ($pernyataan->file_pdf)
                        <a href="{{ asset('storage/' . $pernyataan->file_pdf) }}" target="_blank">Lihat</a>
                    @else
                        Belum Upload
                    @endif
                </td>
                <td style="text-align: center;">
                    @if ($pernyataan->status === 'approved')
                        <span class="badge bg-success">Disetujui</span>
                    @elseif ($pernyataan->status === 'rejected')
                        <span class="badge bg-danger">Ditolak</span>
                    @else
                        <span class="badge bg-secondary">Belum Selesai</span>
                    @endif
                </td>
                <td>
                    <div class="d-flex flex-column gap-1">
                        <div class="d-flex flex-wrap gap-1">
                            <a class="btn btn-sm btn-primary"
                                href="/dashboard/admin/pernyataan-magang/{{ $pernyataan->noSurat }}">Detail</a>
                            <a class="btn btn-sm btn-warning"
                                href="/dashboard/admin/pernyataan-magang/{{ $pernyataan->noSurat }}/edit">Ubah</a>
                            <form id="delete-form-{{ $pernyataan->noSurat }}"
                                action="/dashboard/admin/pernyataan-magang/{{ $pernyataan->noSurat }}" method="post"
                                class="d-inline">
                                @method('delete')
                                @csrf
                                <button type="button" class="btn btn-sm btn-danger border-0"
                                    onclick="confirmDelete('{{ $pernyataan->noSurat }}')">Hapus</button>
                            </form>
                            <a class="btn btn-sm btn-info text-white"
                                href="/dashboard/admin/pernyataan-magang/{{ $pernyataan->noSurat }}/upload">Upload
                                PDF</a>
                        </div>
                        <div class="d-flex flex-wrap gap-1">
                            @if ($pernyataan->status !== 'approved')
                                <button class="btn btn-sm btn-success"
                                    onclick="approveSurat('{{ $pernyataan->noSurat }}')">Setujui</button>
                                <button class="btn btn-sm btn-danger"
                                    onclick="rejectSurat('{{ $pernyataan->noSurat }}')">Tolak</button>
                            @endif
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
