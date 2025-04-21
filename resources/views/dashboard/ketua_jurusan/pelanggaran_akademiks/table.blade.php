<table id="semua-kelas-table" class="table text-start align-middle table-bordered table-hover mb-0">
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
        @foreach ($pelanggaranSemuaKelas as $pelanggaranKelas)
            <tr>
                <td style="white-space: nowrap; text-align: center;">{{ $pelanggaranKelas->noSurat }}</td>
                <td style="white-space: nowrap; text-align: center;">{{ $pelanggaranKelas->nama_mhs }}</td>
                <td style="white-space: nowrap; text-align: center;">{{ $pelanggaranKelas->username }}</td>
                <td style="white-space: nowrap; text-align: center;">{{ $pelanggaranKelas->semester }} /
                    {{ optional($pelanggaranKelas->kelas)->nama_kelas }}</td>
                <td style="white-space: nowrap; text-align: center;">
                    {{ date('d M Y', strtotime($pelanggaranKelas->tglSurat)) }}</td>
                <td style="white-space: nowrap; text-align: center;">
                    @if ($pelanggaranKelas->status_surat == 'selesai')
                        <span class="badge bg-success">Selesai</span>
                    @else
                        <span class="badge bg-warning">Belum Selesai</span>
                    @endif
                </td>
                <td>
                    <div class="d-flex justify-content-start">
                        <a class="btn btn-sm btn-primary me-2"
                            href="/dashboard/ketua-jurusan/pelanggaran-akademik/{{ $pelanggaranKelas->noSurat }}">Detail</a>
                        <a class="btn btn-sm btn-warning me-2"
                            href="/dashboard/ketua-jurusan/pelanggaran-akademik/{{ $pelanggaranKelas->noSurat }}/edit">Edit</a>
                        <form action="/dashboard/ketua-jurusan/pelanggaran-akademik/{{ $pelanggaranKelas->noSurat }}"
                            method="post" class="d-inline" id="delete-form-{{ $pelanggaranKelas->noSurat }}">
                            @method('delete')
                            @csrf
                            <button type="button" class="btn btn-sm btn-danger me-2 border-0"
                                onclick="confirmDelete('{{ $pelanggaranKelas->noSurat }}')">Hapus</button>
                        </form>
                        <a class="btn btn-sm btn-success"
                            href="/dashboard/ketua-jurusan/pelanggaran-akademik/{{ $pelanggaranKelas->noSurat }}/cetak">Cetak</a>
                    </div>
                </td>

            </tr>
        @endforeach
    </tbody>
</table>


<table id="ketua-jurusan-table" class="table text-start align-middle table-bordered table-hover mb-0"
    style="display: none;">
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
        @foreach ($pelanggaranKajurs as $pelanggaranKajur)
            <tr>
                <td style="white-space: nowrap; text-align: center;">{{ $pelanggaranKajur->noSurat }}</td>
                <td style="white-space: nowrap; text-align: center;">{{ $pelanggaranKajur->nama_mhs }}</td>
                <td style="white-space: nowrap; text-align: center;">{{ $pelanggaranKajur->username }}</td>
                <td style="white-space: nowrap; text-align: center;">{{ $pelanggaranKajur->semester }} /
                    {{ optional($pelanggaranKajur->kelas)->nama_kelas }}</td>
                <td style="white-space: nowrap; text-align: center;">
                    {{ date('d M Y', strtotime($pelanggaranKajur->tglSurat)) }}</td>
                <td style="white-space: nowrap; text-align: center;">
                    @if ($pelanggaranKajur->status_surat == 'selesai')
                        <span class="badge bg-success">Selesai</span>
                    @else
                        <span class="badge bg-warning">Belum Selesai</span>
                    @endif
                </td>
                <td>
                    <div class="d-flex justify-content-start">
                        <a class="btn btn-sm btn-primary me-2"
                            href="/dashboard/ketua-jurusan/pelanggaran-akademik/{{ $pelanggaranKajur->noSurat }}">Detail</a>
                        <a class="btn btn-sm btn-warning me-2"
                            href="/dashboard/ketua-jurusan/pelanggaran-akademik/{{ $pelanggaranKajur->noSurat }}/edit">Edit</a>
                        <form action="/dashboard/ketua-jurusan/pelanggaran-akademik/{{ $pelanggaranKajur->noSurat }}"
                            method="post" class="d-inline" id="delete-form-{{ $pelanggaranKajur->noSurat }}">
                            @method('delete')
                            @csrf
                            <button type="button" class="btn btn-sm btn-danger me-2 border-0"
                                onclick="confirmDelete('{{ $pelanggaranKajur->noSurat }}')">Hapus</button>
                        </form>
                        <a class="btn btn-sm btn-success"
                            href="/dashboard/ketua-jurusan/pelanggaran-akademik/{{ $pelanggaranKajur->noSurat }}/cetak">Cetak</a>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
