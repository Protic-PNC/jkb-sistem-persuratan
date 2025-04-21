<table id="admin-table" class="table text-start align-middle table-bordered table-hover mb-0">
    <thead>
        <tr class="text-dark">
            <th scope="col" style="white-space: nowrap; text-align: center;">No. Surat</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">Nama Mahasiswa</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">NPM</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">Semester/Kelas</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">Tanggal Surat</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">Status</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">Action</th>
        </tr>
    </thead>
    <tbody id="results-body">
        @foreach ($pelanggarans as $pelanggaran)
            <tr>
                <td style="white-space: nowrap; text-align: center;">{{ $pelanggaran->noSurat }}</td>
                <td style="white-space: nowrap; text-align: center;">{{ $pelanggaran->nama_mhs }}</td>
                <td style="white-space: nowrap; text-align: center;">{{ $pelanggaran->username }}</td>
                <td style="white-space: nowrap; text-align: center;">{{ $pelanggaran->semester }} /
                    {{ optional($pelanggaran->kelas)->nama_kelas }}</td>
                <td style="white-space: nowrap; text-align: center;">
                    {{ date('d M Y', strtotime($pelanggaran->tglSurat)) }}</td>
                <td style="white-space: nowrap; text-align: center;">
                    @if ($pelanggaran->status_surat == 'selesai')
                        <span class="badge bg-success">Selesai</span>
                    @elseif ($pelanggaran->status_surat == 'ditolak')
                        <span class="badge bg-danger">Ditolak</span>
                    @else
                        <span class="badge bg-warning">Belum Selesai</span>
                    @endif
                </td>
                <td>
                    <div class="d-flex justify-content-start">
                        <a class="btn btn-sm btn-primary me-3"
                            href="/dashboard/admin/pelanggaran-akademik/{{ $pelanggaran->noSurat }}">Detail</a>
                        @if ($pelanggaran->status_surat != 'ditolak')
                            <a class="btn btn-sm btn-warning me-3"
                                href="/dashboard/admin/pelanggaran-akademik/{{ $pelanggaran->noSurat }}/edit">Edit</a>
                        @endif
                        <form action="/dashboard/admin/pelanggaran-akademik/{{ $pelanggaran->noSurat }}" method="post"
                            class="d-inline" id="delete-form-{{ $pelanggaran->noSurat }}">
                            @method('delete')
                            @csrf
                            <button type="button" class="btn btn-sm btn-danger me-3 border-0"
                                onclick="confirmDelete('{{ $pelanggaran->noSurat }}')">Hapus</button>
                        </form>
                        <a class="btn btn-sm btn-success me-3"
                            href="/dashboard/admin/pelanggaran-akademik/{{ $pelanggaran->noSurat }}/cetak">Cetak</a>
                        @if ($pelanggaran->status_surat != 'ditolak')
                            <form action="/dashboard/admin/pelanggaran-akademik/{{ $pelanggaran->noSurat }}/tolak"
                                method="post" class="d-inline" id="tolak-form-{{ $pelanggaran->noSurat }}">
                                @csrf
                                <button type="button" class="btn btn-sm btn-danger"
                                    onclick="confirmTolak('{{ $pelanggaran->noSurat }}')">Tolak</button>
                            </form>
                        @endif
                    </div>
                </td>

            </tr>
        @endforeach
    </tbody>
</table>
