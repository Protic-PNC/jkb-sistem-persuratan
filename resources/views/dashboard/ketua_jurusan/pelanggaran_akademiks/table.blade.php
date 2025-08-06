<table id="semua-kelas-table" class="table text-start align-middle table-bordered table-hover mb-0">
    <thead>
        <tr class="text-dark">
            <th scope="col" style="white-space: nowrap; text-align: center;">No. Surat</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">Nama Mahasiswa</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">NPM</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">Semester/Kelas</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">Tanggal Surat</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">Status</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">Disetujui Oleh</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">Ditolak Oleh</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">Action</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">Persetujuan</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">Alasan</th>
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
                    @php
                        $approved =
                            $pelanggaranKelas->approved_by_admin &&
                            $pelanggaranKelas->approved_by_dosen_wali &&
                            $pelanggaranKelas->approved_by_ketua_jurusan;
                        $rejects =
                            $pelanggaranKelas->rejected_by_admin &&
                            $pelanggaranKelas->rejected_by_dosen_wali &&
                            $pelanggaranKelas->rejected_by_ketua_jurusan;
                    @endphp

                    @if ($rejects)
                        <span class="badge bg-danger">Ditolak</span>
                    @elseif ($approved)
                        <span class="badge bg-success">Disetujui</span>
                    @else
                        <span class="badge bg-warning">Diproses</span>
                    @endif
                </td>
                @php
                    $approvals = [];
                    if ($pelanggaranKelas->approved_by_admin) {
                        $approvals[] = 'Admin';
                    }
                    if ($pelanggaranKelas->approved_by_dosen_wali) {
                        $approvals[] = 'Dosen Wali';
                    }
                    if ($pelanggaranKelas->approved_by_ketua_jurusan) {
                        $approvals[] = 'Ketua Jurusan';
                    }
                @endphp
                <td style="white-space: nowrap; text-align: center;">
                    @if (count($approvals))
                        <span class="badge bg-info text-dark text-white">{{ implode(', ', $approvals) }}</span>
                    @else
                        <span class="text-muted">Belum Ada</span>
                    @endif
                </td>
                @php
                    $rejects = [];
                    if ($pelanggaranKelas->rejected_by_admin && !$pelanggaranKelas->approved_by_admin) {
                        $rejects[] = 'Admin';
                    }
                    if ($pelanggaranKelas->rejected_by_dosen_wali && !$pelanggaranKelas->approved_by_dosen_wali) {
                        $rejects[] = 'Dosen Wali';
                    }
                    if ($pelanggaranKelas->rejected_by_ketua_jurusan && !$pelanggaranKelas->approved_by_ketua_jurusan) {
                        $rejects[] = 'Ketua Jurusan';
                    }
                @endphp
                <td style="white-space: nowrap; text-align: center;">
                    @if (count($rejects))
                        <span class="badge bg-info text-dark text-white">{{ implode(', ', $rejects) }}</span>
                    @else
                        <span class="text-muted">Belum Ada</span>
                    @endif
                </td>
                <td>
                    <div class="d-flex justify-content-start align-items-center gap-2">
                        <a class="btn btn-sm btn-primary"
                            href="/dashboard/ketua-jurusan/pelanggaran-akademik/{{ $pelanggaranKelas->noSurat }}">Detail</a>
                        <a class="btn btn-sm btn-warning text-white"
                            href="/dashboard/ketua-jurusan/pelanggaran-akademik/{{ $pelanggaranKelas->noSurat }}/edit">Ubah</a>
                        <form action="/dashboard/ketua-jurusan/pelanggaran-akademik/{{ $pelanggaranKelas->noSurat }}"
                            method="post" class="d-inline" id="delete-form-{{ $pelanggaranKelas->noSurat }}">
                            @method('delete')
                            @csrf
                            <button type="button" class="btn btn-sm btn-danger border-0"
                                onclick="confirmDelete('{{ $pelanggaranKelas->noSurat }}')">Hapus</button>
                        </form>
                    </div>
                </td>
                <td style="white-space: nowrap; text-align: center;">
                    @if (!$pelanggaranKelas->approved_by_ketua_jurusan)
                        <button type="button" class="btn btn-sm btn-success"
                            onclick="setujuiDoc('{{ $pelanggaranKelas->noSurat }}')">Setujui</button>
                    @endif
                    
                    @if (!$pelanggaranKelas->rejected_by_ketua_jurusan)
                        <button type="button" class="btn btn-sm btn-danger"
                            onclick="tolakDoc('{{ $pelanggaranKelas->noSurat }}')">Tolak</button>
                    @endif
                    
                    @if ($pelanggaranKelas->rejected_by_ketua_jurusan && !$pelanggaranKelas->approved_by_ketua_jurusan)
                        <button type="button" class="btn btn-sm btn-warning text-white"
                            onclick="editAlasan('{{ $pelanggaranKelas->noSurat }}', `{!! addslashes($pelanggaranKelas->alasan ?? '') !!}`, 'ketua_jurusan', '/dashboard/ketua-jurusan/pelanggaran-akademik')">
                            Ubah Alasan
                        </button>
                    @endif
                </td>
                <td style="text-align: center;">
                    @if ($pelanggaranKelas->alasan)
                        <button class="btn btn-sm btn-outline-danger"
                            onclick="showAlasanPelanggaran(`{!! addslashes($pelanggaranKelas->alasan) !!}`, 'Ketua jurusan')"
                            style="white-space: nowrap; padding: 5px 15px;">Lihat Alasan</button>
                    @else
                        <span class="text-muted">Belum Ada</span>
                    @endif
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
            <th scope="col" style="white-space: nowrap; text-align: center;">Disetujui Oleh</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">Ditolak Oleh</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">Action</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">Persetujuan</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">Alasan</th>
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
                    @php
                        $approved =
                            $pelanggaranKajur->approved_by_admin &&
                            $pelanggaranKajur->approved_by_dosen_wali &&
                            $pelanggaranKajur->approved_by_ketua_jurusan;
                        $rejects =
                            $pelanggaranKajur->rejected_by_admin &&
                            $pelanggaranKajur->rejected_by_dosen_wali &&
                            $pelanggaranKajur->rejected_by_ketua_jurusan;
                    @endphp

                    @if ($rejects)
                        <span class="badge bg-danger">Ditolak</span>
                    @elseif ($approved)
                        <span class="badge bg-success">Disetujui</span>
                    @else
                        <span class="badge bg-warning">Diproses</span>
                    @endif
                </td>
                @php
                    $approvals = [];
                    if ($pelanggaranKajur->approved_by_admin) {
                        $approvals[] = 'Admin';
                    }
                    if ($pelanggaranKajur->approved_by_dosen_wali) {
                        $approvals[] = 'Dosen Wali';
                    }
                    if ($pelanggaranKajur->approved_by_ketua_jurusan) {
                        $approvals[] = 'Ketua Jurusan';
                    }
                @endphp
                <td style="white-space: nowrap; text-align: center;">
                    @if (count($approvals))
                        <span class="badge bg-info text-dark text-white">{{ implode(', ', $approvals) }}</span>
                    @else
                        <span class="text-muted">Belum Ada</span>
                    @endif
                </td>
                @php
                    $rejects = [];
                    if ($pelanggaranKajur->rejected_by_admin && !$pelanggaranKajur->approved_by_admin) {
                        $rejects[] = 'Admin';
                    }
                    if ($pelanggaranKajur->rejected_by_dosen_wali && !$pelanggaranKajur->approved_by_dosen_wali) {
                        $rejects[] = 'Dosen Wali';
                    }
                    if ($pelanggaranKajur->rejected_by_ketua_jurusan && !$pelanggaranKajur->approved_by_ketua_jurusan) {
                        $rejects[] = 'Ketua Jurusan';
                    }
                @endphp

                <td style="white-space: nowrap; text-align: center;">
                    @if (count($rejects))
                        <span class="badge bg-info text-dark text-white">{{ implode(', ', $rejects) }}</span>
                    @else
                        <span class="text-muted">Belum Ada</span>
                    @endif
                </td>
                <td>
                    <div class="d-flex justify-content-start align-items-center gap-2">
                        <a class="btn btn-sm btn-primary"
                            href="/dashboard/ketua-jurusan/pelanggaran-akademik/{{ $pelanggaranKajur->noSurat }}">Detail</a>
                        <a class="btn btn-sm btn-warning text-white"
                            href="/dashboard/ketua-jurusan/pelanggaran-akademik/{{ $pelanggaranKajur->noSurat }}/edit">Ubah</a>
                        <form action="/dashboard/ketua-jurusan/pelanggaran-akademik/{{ $pelanggaranKajur->noSurat }}"
                            method="post" class="d-inline" id="delete-form-{{ $pelanggaranKajur->noSurat }}">
                            @method('delete')
                            @csrf
                            <button type="button" class="btn btn-sm btn-danger border-0"
                                onclick="confirmDelete('{{ $pelanggaranKajur->noSurat }}')">Hapus</button>
                        </form>
                    </div>
                </td>
                <td style="white-space: nowrap; text-align: center;">
                    @if (!$pelanggaranKajur->approved_by_ketua_jurusan)
                        <button type="button" class="btn btn-sm btn-success"
                            onclick="setujuiDoc('{{ $pelanggaranKajur->noSurat }}')">Setujui</button>
                    @endif
                    
                    @if (!$pelanggaranKajur->rejected_by_ketua_jurusan)
                        <button type="button" class="btn btn-sm btn-danger"
                            onclick="tolakDoc('{{ $pelanggaranKajur->noSurat }}')">Tolak</button>
                    @endif
                    
                    @if ($pelanggaranKajur->rejected_by_ketua_jurusan && !$pelanggaranKajur->approved_by_ketua_jurusan)
                        <button type="button" class="btn btn-sm btn-warning text-white"
                            onclick="editAlasan('{{ $pelanggaranKajur->noSurat }}', `{!! addslashes($pelanggaranKajur->alasan ?? '') !!}`, 'ketua_jurusan', '/dashboard/ketua-jurusan/pelanggaran-akademik')">
                            Ubah Alasan
                        </button>
                    @endif
                </td>
                <td style="text-align: center;">
                    @if ($pelanggaranKajur->alasan)
                        <button class="btn btn-sm btn-outline-danger"
                            onclick="showAlasanPelanggaran(`{!! addslashes($pelanggaranKajur->alasan) !!}`, 'Ketua jurusan')"
                            style="white-space: nowrap; padding: 5px 15px;">Lihat Alasan</button>
                    @else
                        <span class="text-muted">Belum Ada</span>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<script>
    function confirmDelete(noSurat) {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: 'Apakah Anda yakin ingin menghapus dokumen ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${noSurat}`).submit();
            }
        });
    }
</script>
