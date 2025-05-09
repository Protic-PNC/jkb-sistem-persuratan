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
                    @if ($pelanggaran->status_surat == 'aprroved')
                        <span class="badge bg-success">Disetujui</span>
                    @elseif ($pelanggaran->status_surat == 'rejected')
                        <span class="badge bg-danger">Ditolak</span>
                    @else
                        <span class="badge bg-warning">Diproses</span>
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
                        @if ($pelanggaran->status_surat != 'disetujui')
                            <form action="/dashboard/admin/pelanggaran-akademik/{{ $pelanggaran->noSurat }}/setujui"
                                method="post" class="d-inline">
                                @csrf
                                <button type="button" class="btn btn-sm btn-success"
                                    onclick="approveAdminSuratPelanggaran('{{ $pelanggaran->noSurat }}')">Setujui</button>
                            </form>
                        @endif
                        @if ($pelanggaran->status_surat != 'ditolak')
                            <form action="/dashboard/admin/pelanggaran-akademik/{{ $pelanggaran->noSurat }}/tolak"
                                method="post" class="d-inline" id="tolak-form-{{ $pelanggaran->noSurat }}">
                                @csrf
                                <button type="button" class="btn btn-sm btn-danger"
                                    onclick="rejectAdminSuratPelanggaran('{{ $pelanggaran->noSurat }}')">Tolak</button>
                            </form>
                        @endif
                        <button class="btn btn-sm" style="background-color: #ff9800; color: #fff; border: none;"
                            onclick="sendPelanggaranReminder('{{ $pelanggaran->noSurat }}')">Pengingat</button>


                    </div>
                </td>

                <td style="text-align: center;">
                    @if ($pelanggaran->status_surat == 'rejected' && $pelanggaran->alasan)
                        <button class="btn btn-sm btn-outline-danger"
                            onclick="showAlasanPelanggaran(`{!! addslashes($pelanggaran->alasan) !!}`)"
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
