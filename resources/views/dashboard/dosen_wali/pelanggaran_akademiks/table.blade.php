<!-- Tabel Pelanggaran Akademik Kelas Perwalian -->
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
            <th scope="col" style="white-space: nowrap; text-align: center;">Persetujuan</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">Alasan</th>
        </tr>
    </thead>
    <tbody id="results-body">
        @foreach ($pelanggaranSemuaKelasDosen as $pelanggaran)
            <tr>
                <td style="white-space: nowrap; text-align: center;">{{ $pelanggaran->noSurat }}</td>
                <td style="white-space: nowrap; text-align: center;">{{ $pelanggaran->nama_mhs }}</td>
                <td style="white-space: nowrap; text-align: center;">{{ $pelanggaran->username }}</td>
                <td style="white-space: nowrap; text-align: center;">{{ $pelanggaran->semester }} /
                    {{ optional($pelanggaran->kelas)->nama_kelas }}</td>
                <td style="white-space: nowrap; text-align: center;">
                    {{ date('d M Y', strtotime($pelanggaran->tglSurat)) }}</td>
                <td style="white-space: nowrap; text-align: center;">
                    @if ($pelanggaran->status_surat == 'approved')
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
                            href="/dashboard/dosen-wali/pelanggaran-akademik/{{ $pelanggaran->noSurat }}">Detail</a>
                        <a class="btn btn-sm btn-warning text-white"
                            href="/dashboard/dosen-wali/pelanggaran-akademik/{{ $pelanggaran->noSurat }}/edit">Ubah</a>
                        <form action="/dashboard/dosen-wali/pelanggaran-akademik/{{ $pelanggaran->noSurat }}"
                            method="post" class="d-inline" id="delete-form-{{ $pelanggaran->noSurat }}">
                            @method('delete')
                            @csrf
                            <button type="button" class="btn btn-sm btn-danger border-0"
                                onclick="confirmDelete('{{ $pelanggaran->noSurat }}')">Hapus</button>
                        </form>
                    </div>
                </td>
                <td style="white-space: nowrap; text-align: center;">
                    @if ($pelanggaran->status_surat == 'rejected')
                        <form action="/dashboard/dosen-wali/pelanggaran-akademik/{{ $pelanggaran->noSurat }}/setujui"
                            method="post" class="d-inline">
                            @csrf
                            <button type="button" class="btn btn-sm btn-success"
                                onclick="approveDosenSuratPelanggaran('{{ $pelanggaran->noSurat }}')">Setujui</button>
                        </form>
                        <button type="button" class="btn btn-sm btn-warning text-white"
                            onclick="editAlasan('{{ $pelanggaran->noSurat }}', `{!! addslashes($pelanggaran->alasan ?? '') !!}`)">
                            Ubah Alasan
                        </button>
                    @elseif ($pelanggaran->status_surat == 'diproses')
                        <form action="/dashboard/dosen-wali/pelanggaran-akademik/{{ $pelanggaran->noSurat }}/setujui"
                            method="post" class="d-inline">
                            @csrf
                            <button type="button" class="btn btn-sm btn-success"
                                onclick="approveDosenSuratPelanggaran('{{ $pelanggaran->noSurat }}')">Setujui</button>
                        </form>
                        <form action="/dashboard/dosen-wali/pelanggaran-akademik/{{ $pelanggaran->noSurat }}/tolak"
                            method="post" class="d-inline" id="tolak-form-{{ $pelanggaran->noSurat }}">
                            @csrf
                            <button type="button" class="btn btn-sm btn-danger"
                                onclick="rejectDosenSuratPelanggaran('{{ $pelanggaran->noSurat }}')">Tolak</button>
                        </form>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td style="text-align: center;">
                    @if ($pelanggaran->status_surat == 'rejected' && $pelanggaran->alasan)
                        <button class="btn btn-sm btn-outline-danger"
                            onclick="showAlasanPelanggaran(`{!! addslashes($pelanggaran->alasan) !!}`)"
                            style="white-space: nowrap; padding: 5px 15px;">Lihat Alasan</button>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<!-- Tabel Pelanggaran Akademik Sebagai Pelapor -->
<table id="dosen-table" class="table text-start align-middle table-bordered table-hover mb-0">
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
        @foreach ($pelanggaranDosens as $pelanggaran)
            <tr>
                <td style="white-space: nowrap; text-align: center;">{{ $pelanggaran->noSurat }}</td>
                <td style="white-space: nowrap; text-align: center;">{{ $pelanggaran->nama_mhs }}</td>
                <td style="white-space: nowrap; text-align: center;">{{ $pelanggaran->username }}</td>
                <td style="white-space: nowrap; text-align: center;">{{ $pelanggaran->semester }} /
                    {{ optional($pelanggaran->kelas)->nama_kelas }}</td>
                <td style="white-space: nowrap; text-align: center;">
                    {{ date('d M Y', strtotime($pelanggaran->tglSurat)) }}</td>
                <td style="white-space: nowrap; text-align: center;">
                    @if ($pelanggaran->status_surat == 'approved')
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
                            href="/dashboard/dosen-wali/pelanggaran-akademik/{{ $pelanggaran->noSurat }}">Detail</a>
                        <a class="btn btn-sm btn-warning text-white"
                            href="/dashboard/dosen-wali/pelanggaran-akademik/{{ $pelanggaran->noSurat }}/edit">Ubah</a>
                        <form action="/dashboard/dosen-wali/pelanggaran-akademik/{{ $pelanggaran->noSurat }}"
                            method="post" class="d-inline" id="delete-form-{{ $pelanggaran->noSurat }}">
                            @method('delete')
                            @csrf
                            <button type="button" class="btn btn-sm btn-danger border-0"
                                onclick="confirmDelete('{{ $pelanggaran->noSurat }}')">Hapus</button>
                        </form>
                    </div>
                </td>
                <td style="text-align: center;">
                    @if ($pelanggaran->status_surat == 'rejected' && $pelanggaran->alasan)
                        <button class="btn btn-sm btn-outline-danger"
                            onclick="showAlasanPelanggaran(`{!! addslashes($pelanggaran->alasan) !!}`)"
                            style="white-space: nowrap; padding: 5px 15px;">Lihat Alasan</button>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="modal fade" id="editAlasanModal" tabindex="-1" aria-labelledby="editAlasanLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="post" action="" id="editAlasanForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAlasanLabel">Ubah Alasan Penolakan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="alasanInput" class="form-label">Alasan</label>
                        <textarea class="form-control" id="alasanInput" name="alasan" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    let originalAlasan = "";

    function editAlasan(noSurat, alasan) {
        const form = document.getElementById("editAlasanForm");
        if (!form) return;

        form.setAttribute(
            "action",
            `/dashboard/dosen-wali/pelanggaran-akademik/${noSurat}/edit-alasan`
        );
        document.getElementById("alasanInput").value = alasan;
        originalAlasan = alasan;
        const modal = new bootstrap.Modal(
            document.getElementById("editAlasanModal")
        );
        modal.show();
    }

    document.addEventListener("DOMContentLoaded", function() {
        const editAlasanForm = document.getElementById("editAlasanForm");
        if (editAlasanForm) {
            editAlasanForm.addEventListener("submit", async function(e) {
                e.preventDefault();

                const form = e.target;
                const url = form.getAttribute("action");
                const formData = new FormData(form);
                const currentAlasan = formData.get("alasan").trim();

                if (currentAlasan === originalAlasan.trim()) {
                    Swal.fire({
                        icon: "info",
                        title: "Tidak Ada Perubahan",
                        text: "Alasan tidak diubah.",
                        timer: 2000,
                        showConfirmButton: false,
                    });
                    return;
                }

                try {
                    const submitButton = form.querySelector(
                        'button[type="submit"]'
                    );
                    if (submitButton) {
                        submitButton.disabled = true;
                    }

                    const response = await fetch(url, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute("content"),
                            Accept: "application/json",
                        },
                        body: formData,
                    });

                    const result = await response.json();

                    if (submitButton) {
                        submitButton.disabled = false;
                    }

                    if (response.ok) {
                        const modal = bootstrap.Modal.getInstance(
                            document.getElementById("editAlasanModal")
                        );
                        modal.hide();

                        Swal.fire({
                            icon: "success",
                            title: "Berhasil!",
                            text: result.message || "Alasan berhasil diperbarui.",
                            timer: 2000,
                            showConfirmButton: false,
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        throw new Error(
                            result.message || "Gagal memperbarui alasan."
                        );
                    }
                } catch (error) {
                    const submitButton = form.querySelector(
                        'button[type="submit"]'
                    );
                    if (submitButton) {
                        submitButton.disabled = false;
                    }

                    Swal.fire({
                        icon: "error",
                        title: "Terjadi Kesalahan",
                        text: error.message,
                    });
                }
            });
        }
    });
</script>
