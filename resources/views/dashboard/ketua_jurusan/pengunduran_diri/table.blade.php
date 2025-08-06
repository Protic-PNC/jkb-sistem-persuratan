<table id="ketua-jurusan-second-table" class="table text-start align-middle table-bordered table-hover mb-0">
    <thead>
        <tr class="text-dark">
            <th scope="col" style="white-space: nowrap; text-align: center;">No. Surat</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">Nama Mahasiswa</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">NPM</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">Semester/Kelas</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">Tanggal Surat</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">Berkas</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">Status</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">Action</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">Persetujuan</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">Alasan</th>
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
                    @if ($pengunduran->file_pdf)
                        <a href="{{ asset('storage/' . $pengunduran->file_pdf) }}" target="_blank">Lihat</a>
                    @else
                        Belum Upload
                    @endif
                </td>
                <td style="white-space: nowrap; text-align: center;">
                    @if ($pengunduran->status_surat == 'selesai')
                        <span class="badge bg-success">Disetujui</span>
                    @elseif ($pengunduran->status_surat == 'ditolak')
                        <span class="badge bg-danger">Ditolak</span>
                    @else
                        <span class="badge bg-warning">Diproses</span>
                    @endif
                </td>
                <td style="max-width: 100%; overflow-x: auto;">
                    <div class="d-flex flex-column gap-1" style="min-width: max-content;">
                        <div class="d-flex flex-nowrap gap-1">
                            <a class="btn btn-sm btn-primary"
                                href="/dashboard/ketua-jurusan/pengunduran-diri/{{ $pengunduran->noSurat }}">Detail</a>
                            @if ($pengunduran->status_surat != 'ditolak')
                                <a class="btn btn-sm btn-warning text-white"
                                    href="/dashboard/ketua-jurusan/pengunduran-diri/{{ $pengunduran->noSurat }}/edit">Ubah</a>
                            @endif
                            <form id="delete-form-{{ $pengunduran->noSurat }}"
                                action="/dashboard/ketua-jurusan/pengunduran-diri/{{ $pengunduran->noSurat }}" method="post"
                                class="d-inline">
                                @method('delete')
                                @csrf
                                <button type="button" class="btn btn-sm btn-danger border-0"
                                    onclick="confirmDelete('{{ $pengunduran->noSurat }}')">Hapus</button>
                            </form>
                            <a class="btn btn-sm btn-success"
                                href="/dashboard/ketua-jurusan/pengunduran-diri/{{ $pengunduran->noSurat }}/cetak">Cetak</a>
                        </div>
                    </div>
                </td>
                <td style="text-align: center;">
                    @if ($pengunduran->status_surat != 'selesai' && $pengunduran->status_surat != 'ditolak')
                        <div class="d-flex flex-nowrap justify-content-center gap-1" style="min-width: max-content;">
                            <button class="btn btn-sm btn-success"
                                onclick="approveKajurSuratPengunduran('{{ $pengunduran->noSurat }}')">Setujui</button>
                            <button class="btn btn-sm btn-danger"
                                onclick="rejectKajurSuratPengunduran('{{ $pengunduran->noSurat }}')">Tolak</button>
                        </div>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td style="text-align: center;">
                    @if ($pengunduran->status_surat == 'ditolak' && $pengunduran->alasan)
                        <button class="btn btn-sm btn-outline-danger"
                            onclick="showAlasanPengunduran(`{!! addslashes($pengunduran->alasan) !!}`)"
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

<script>
    function confirmDelete(noSurat) {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: 'Apakah Anda yakin ingin menghapus dokumen ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${noSurat}`).submit();
            }
        });
    }

    function approveKajurSuratPengunduran(noSurat) {
        Swal.fire({
            title: 'Konfirmasi Persetujuan',
            text: 'Apakah Anda yakin ingin menyetujui pengunduran diri ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Setujui!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/dashboard/ketua-jurusan/pengunduran-diri/${noSurat}/setujui`,
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire('Berhasil!', response.message, 'success').then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire('Gagal!', xhr.responseJSON.message || 'Terjadi kesalahan', 'error');
                    }
                });
            }
        });
    }

    function rejectKajurSuratPengunduran(noSurat) {
        Swal.fire({
            title: 'Alasan Penolakan',
            text: 'Masukkan alasan penolakan surat pengunduran diri:',
            input: 'textarea',
            inputPlaceholder: 'Ketik alasan penolakan di sini...',
            showCancelButton: true,
            confirmButtonText: 'Tolak',
            cancelButtonText: 'Batal',
            showLoaderOnConfirm: true,
            preConfirm: (alasan) => {
                if (!alasan) {
                    Swal.showValidationMessage('Alasan penolakan harus diisi!');
                    return false;
                }
                return $.ajax({
                    url: `/dashboard/ketua-jurusan/pengunduran-diri/${noSurat}/tolak`,
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        alasan: alasan
                    }
                }).then(response => {
                    return response;
                }).catch(error => {
                    Swal.showValidationMessage(`Request failed: ${error.responseJSON.message || 'Terjadi kesalahan'}`);
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Ditolak!', result.value.message, 'success').then(() => {
                    location.reload();
                });
            }
        });
    }

    function showAlasanPengunduran(alasan) {
        Swal.fire({
            title: 'Alasan Penolakan',
            html: `<div style="text-align: left; white-space: pre-wrap;">${alasan}</div>`,
            icon: 'info',
            confirmButtonText: 'Tutup'
        });
    }
</script>
