<table id="kelas-table" class="table text-start align-middle table-bordered table-hover mb-0">
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
        @foreach ($pelanggaranSemuaKelasDosen as $pelanggaranKelasDosen)
            <tr>
                <td style="white-space: nowrap; text-align: center;">{{ $pelanggaranKelasDosen->noSurat }}</td>
                <td style="white-space: nowrap; text-align: center;">{{ $pelanggaranKelasDosen->nama_mhs }}</td>
                <td style="white-space: nowrap; text-align: center;">{{ $pelanggaranKelasDosen->username }}</td>
                <td style="white-space: nowrap; text-align: center;">{{ $pelanggaranKelasDosen->semester }} /
                    {{ optional($pelanggaranKelasDosen->kelas)->nama_kelas }}</td>
                <td style="white-space: nowrap; text-align: center;">
                    {{ date('d M Y', strtotime($pelanggaranKelasDosen->tglSurat)) }}</td>
                <td style="white-space: nowrap; text-align: center;">
                    @if ($pelanggaranKelasDosen->status_surat == 'selesai')
                        <span class="badge bg-success">Selesai</span>
                    @else
                        <span class="badge bg-warning">Belum Selesai</span>
                    @endif
                </td>
                <td>
                    <div class="d-flex justify-content-start">
                        <a class="btn btn-sm btn-primary me-2"
                            href="/dashboard/dosen-wali/pelanggaran-akademik/{{ $pelanggaranKelasDosen->noSurat }}">Detail</a>
                        <a class="btn btn-sm btn-warning me-2"
                            href="/dashboard/dosen-wali/pelanggaran-akademik/{{ $pelanggaranKelasDosen->noSurat }}/edit">Edit</a>
                        <form action="/dashboard/dosen-wali/pelanggaran-akademik/{{ $pelanggaranKelasDosen->noSurat }}"
                            method="post" class="d-inline" id="delete-form-{{ $pelanggaranKelasDosen->noSurat }}">
                            @method('delete')
                            @csrf
                            <button type="button" class="btn btn-sm btn-danger me-2 border-0"
                                onclick="confirmDelete('{{ $pelanggaranKelasDosen->noSurat }}')">Hapus</button>
                        </form>
                        <a class="btn btn-sm btn-success"
                            href="/dashboard/dosen-wali/pelanggaran-akademik/{{ $pelanggaranKelasDosen->noSurat }}/cetak">Cetak</a>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<table id="dosen-table" class="table text-start align-middle table-bordered table-hover mb-0" style="display: none;">
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
        @foreach ($pelanggaranDosens as $pelanggaranDosen)
            <tr>
                <td style="white-space: nowrap; text-align: center;">{{ $pelanggaranDosen->noSurat }}</td>
                <td style="white-space: nowrap; text-align: center;">{{ $pelanggaranDosen->nama_mhs }}</td>
                <td style="white-space: nowrap; text-align: center;">{{ $pelanggaranDosen->username }}</td>
                <td style="white-space: nowrap; text-align: center;">{{ $pelanggaranDosen->semester }} /
                    {{ optional($pelanggaranDosen->kelas)->nama_kelas }}</td>
                <td style="white-space: nowrap; text-align: center;">
                    {{ date('d M Y', strtotime($pelanggaranDosen->tglSurat)) }}</td>
                <td style="white-space: nowrap; text-align: center;">
                    @if ($pelanggaranDosen->status_surat == 'selesai')
                        <span class="badge bg-success">Selesai</span>
                    @else
                        <span class="badge bg-warning">Belum Selesai</span>
                    @endif
                </td>
                <td>
                    <div class="d-flex justify-content-start">
                        <a class="btn btn-sm btn-primary me-2"
                            href="/dashboard/dosen-wali/pelanggaran-akademik/{{ $pelanggaranDosen->noSurat }}">Detail</a>
                        <a class="btn btn-sm btn-warning me-2"
                            href="/dashboard/dosen-wali/pelanggaran-akademik/{{ $pelanggaranDosen->noSurat }}/edit">Edit</a>
                        <form action="/dashboard/dosen-wali/pelanggaran-akademik/{{ $pelanggaranDosen->noSurat }}"
                            method="post" class="d-inline" id="delete-form-{{ $pelanggaranDosen->noSurat }}">
                            @method('delete')
                            @csrf
                            <button type="button" class="btn btn-sm btn-danger me-2 border-0"
                                onclick="confirmDelete('{{ $pelanggaranDosen->noSurat }}')">Hapus</button>
                        </form>
                        <a class="btn btn-sm btn-success"
                            href="/dashboard/dosen-wali/pelanggaran-akademik/{{ $pelanggaranDosen->noSurat }}/cetak">Cetak</a>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
