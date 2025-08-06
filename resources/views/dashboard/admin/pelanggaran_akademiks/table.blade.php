<table id="admin-table" class="table text-start align-middle table-bordered table-hover mb-0">
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
                    @php
                        $approved =
                            $pelanggaran->approved_by_admin &&
                            $pelanggaran->approved_by_dosen_wali &&
                            $pelanggaran->approved_by_ketua_jurusan;
                        $rejects =
                            $pelanggaran->rejected_by_admin &&
                            $pelanggaran->rejected_by_dosen_wali &&
                            $pelanggaran->rejected_by_ketua_jurusan;
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
                    if ($pelanggaran->approved_by_admin) {
                        $approvals[] = 'Admin';
                    }
                    if ($pelanggaran->approved_by_dosen_wali) {
                        $approvals[] = 'Dosen Wali';
                    }
                    if ($pelanggaran->approved_by_ketua_jurusan) {
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
                    if ($pelanggaran->rejected_by_admin && !$pelanggaran->approved_by_admin) {
                        $rejects[] = 'Admin';
                    }
                    if ($pelanggaran->rejected_by_dosen_wali && !$pelanggaran->approved_by_dosen_wali) {
                        $rejects[] = 'Dosen Wali';
                    }
                    if ($pelanggaran->rejected_by_ketua_jurusan && !$pelanggaran->approved_by_ketua_jurusan) {
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
                            href="/dashboard/admin/pelanggaran-akademik/{{ $pelanggaran->noSurat }}">Detail</a>
                        @if ($pelanggaran->status_surat != 'ditolak')
                            <a class="btn btn-sm btn-warning text-white"
                                href="/dashboard/admin/pelanggaran-akademik/{{ $pelanggaran->noSurat }}/edit">Ubah</a>
                        @endif
                        <form action="/dashboard/admin/pelanggaran-akademik/{{ $pelanggaran->noSurat }}" method="post"
                            class="d-inline" id="delete-form-{{ $pelanggaran->noSurat }}">
                            @method('delete')
                            @csrf
                            <button type="button" class="btn btn-sm btn-danger border-0"
                                onclick="confirmDelete('{{ $pelanggaran->noSurat }}')">Hapus</button>
                        </form>
                        <button class="btn btn-sm" style="background-color: #ff9800; color: #fff; border: none;"
                            onclick="sendPelanggaranReminder('{{ $pelanggaran->noSurat }}')">Pengingat</button>


                    </div>
                </td>
                <td style="white-space: nowrap; text-align: center;">
                    @if (!$pelanggaran->approved_by_admin)
                    <form action="/dashboard/admin/pelanggaran-akademik/{{ $pelanggaran->noSurat }}/setujui"
                        method="post" class="d-inline">
                        @csrf
                        <button type="button" class="btn btn-sm btn-success"
                            onclick="approveAdminSuratPelanggaran('{{ $pelanggaran->noSurat }}')">Setujui</button>
                    </form>
                    @endif
                    
                    @if (!$pelanggaran->rejected_by_admin)
                    <form action="/dashboard/admin/pelanggaran-akademik/{{ $pelanggaran->noSurat }}/tolak"
                        method="post" class="d-inline" id="tolak-form-{{ $pelanggaran->noSurat }}">
                        @csrf
                        <button type="button" class="btn btn-sm btn-danger"
                            onclick="rejectAdminSuratPelanggaran('{{ $pelanggaran->noSurat }}')">Tolak</button>
                    </form>
                    @endif
                    
                    @if ($pelanggaran->rejected_by_admin && !$pelanggaran->approved_by_admin)
                    <button type="button" class="btn btn-sm btn-warning text-white"
                        onclick="editAlasan('{{ $pelanggaran->noSurat }}', `{!! addslashes($pelanggaran->alasan ?? '') !!}`, 'admin', '/dashboard/admin/pelanggaran-akademik')">
                        Ubah Alasan
                    </button>
                    @endif
                </td>
                <td style="text-align: center;">
                    @if ($pelanggaran->status_surat == 'diproses' && $pelanggaran->alasan)
                        <button class="btn btn-sm btn-outline-danger"
                            onclick="showAlasanPelanggaran(`{!! addslashes($pelanggaran->alasan) !!}`, 'Admin')"
                            style="white-space: nowrap; padding: 5px 15px;">
                            Lihat Alasan
                        </button>
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
