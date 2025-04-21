<table id="keuangan-table" class="table text-start align-middle table-bordered table-hover mb-0">
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
        @foreach ($pengundurans as $pengunduran)
            <tr>
                <td style="white-space: nowrap; text-align: center;">{{ $pengunduran->noSurat }}</td>
                <td style="white-space: nowrap; text-align: center;">{{ $pengunduran->nama_mhs }}</td>
                <td style="white-space: nowrap; text-align: center;">{{ $pengunduran->username }}</td>
                <td style="white-space: nowrap; text-align: center;">{{ $pengunduran->semester }} /
                    {{ optional($pengunduran->kelas)->nama_kelas }}</td>
                <td style="white-space: nowrap; text-align: center;">
                    {{ date('d M Y', strtotime($pengunduran->tglSurat)) }}</td>
                <td style="white-space: nowrap; text-align: center;">
                    @if ($pengunduran->status_surat == 'selesai')
                        <span class="badge bg-success">Selesai</span>
                    @elseif ($pengunduran->status_surat == 'ditolak')
                        <span class="badge bg-danger">Ditolak</span>
                    @else
                        <span class="badge bg-warning">Belum Selesai</span>
                    @endif
                </td>
                <td>
                    <div class="d-flex justify-content-start">
                        <a class="btn btn-sm btn-primary me-3"
                            href="/dashboard/bagian-keuangan/pengunduran-diri/{{ $pengunduran->noSurat }}">Detail</a>
                        @if ($pengunduran->status_surat != 'ditolak')
                            <a class="btn btn-sm btn-warning me-3"
                                href="/dashboard/bagian-keuangan/pengunduran-diri/{{ $pengunduran->noSurat }}/edit">Edit</a>
                        @endif
                        <form action="/dashboard/bagian-keuangan/pengunduran-diri/{{ $pengunduran->noSurat }}"
                            method="post" class="d-inline" id="delete-form-{{ $pengunduran->noSurat }}">
                            @method('delete')
                            @csrf
                            <button type="button" class="btn btn-sm btn-danger me-3 border-0"
                                onclick="confirmDelete('{{ $pengunduran->noSurat }}')">Hapus</button>
                        </form>
                        <a class="btn btn-sm btn-success me-3"
                            href="/dashboard/bagian-keuangan/pengunduran-diri/{{ $pengunduran->noSurat }}/cetak">Cetak</a>
                        @if ($pengunduran->status_surat != 'ditolak')
                            <form action="/dashboard/bagian-keuangan/pengunduran-diri/{{ $pengunduran->noSurat }}/tolak"
                                method="post" class="d-inline" id="tolak-form-{{ $pengunduran->noSurat }}">
                                @csrf
                                <button type="button" class="btn btn-sm btn-danger"
                                    onclick="confirmTolak('{{ $pengunduran->noSurat }}')">Tolak</button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
