@extends('dashboard.mahasiswa.layouts.main')

@section('container')
    <div class="container-fluid mb-4 pt-4 px-4">
        <div class="row g-4 justify-content-center">
            <div class="col-sm-12 col-xl-8">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">Edit Surat Peringatan karena Pelanggaran Peraturan Akademik</h6>
                    <form id="update-form-pelanggaran" method="post" enctype="multipart/form-data"
                        action="/dashboard/mahasiswa/pelanggaran-akademik/{{ $pelanggarans->noSurat }}">
                        @method('put')
                        @csrf
                        <input type="hidden" name="kelas_id" value="{{ $pelanggarans->kelas_id }}">
                        <input type="hidden" name="peringatan" value="{{ $pelanggarans->peringatan }}">
                        <input type="hidden" id="original_nama_mhs" value="{{ $pelanggarans->nama_mhs }}">
                        <input type="hidden" id="original_nama_pelapor" value="{{ $pelanggarans->nama_pelapor }}">
                        <input type="hidden" id="original_nama_dosen_wali" value="{{ $pelanggarans->nama_dosen_wali }}">
                        <input type="hidden" id="original_nama_ketua_jurusan"
                            value="{{ $pelanggarans->nama_ketua_jurusan }}">
                        <input type="hidden" id="original_username" value="{{ $pelanggarans->username }}">
                        <input type="hidden" id="original_semester" value="{{ $pelanggarans->semester }}">
                        <input type="hidden" id="original_kelas" value="{{ $pelanggarans->kelas_id }}">
                        <input type="hidden" id="original_peringatan" value="{{ $pelanggarans->peringatan }}">
                        <input type="hidden" id="original_hari" value="{{ $pelanggarans->hari }}">
                        <input type="hidden" id="original_tglSurat" value="{{ $pelanggarans->tglSurat }}">
                        <input type="hidden" id="original_pasal" value="{{ $pelanggarans->pasal }}">
                        <input type="hidden" id="original_isi_pasal" value="{{ $pelanggarans->isi_pasal }}">
                        <div class="mb-3">
                            <label for="nama_pelapor" class="form-label">Nama Pelapor</label>
                            <input type="text" class="form-control @error('nama_pelapor') is-invalid @enderror"
                                id="nama_pelapor" name="nama_pelapor"
                                value="{{ old('nama_pelapor', $pelanggarans->nama_pelapor) }}" readonly>
                            @error('nama_pelapor')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nama_dosen_wali" class="form-label">Nama Dosen Wali</label>
                            <input type="text" class="form-control @error('nama_dosen_wali') is-invalid @enderror"
                                id="nama_dosen_wali" name="nama_dosen_wali"
                                value="{{ old('nama_dosen_wali', $pelanggarans->nama_dosen_wali) }}" readonly>
                            @error('nama_dosen_wali')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="nama_ketua_jurusan" class="form-label">Nama Ketua Jurusan</label>
                            <input type="text" class="form-control @error('nama_ketua_jurusan') is-invalid @enderror"
                                id="nama_ketua_jurusan" name="nama_ketua_jurusan"
                                value="{{ old('nama_ketua_jurusan', $pelanggarans->nama_ketua_jurusan) }}" readonly>
                            @error('nama_ketua_jurusan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="nama_mhs" class="form-label">Kepada</label>
                            <input type="text" class="form-control @error('nama_mhs') is-invalid @enderror"
                                id="nama_mhs" name="nama_mhs" value="{{ old('nama_mhs', $pelanggarans->nama_mhs) }}"
                                readonly>
                            @error('nama_mhs')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">NPM</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror"
                                id="username" name="username" value="{{ old('username', $pelanggarans->username) }}"
                                readonly>
                            @error('username')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="semester" class="form-label">Semester</label>
                            <input type="text" class="form-control @error('semester') is-invalid @enderror"
                                id="semester" name="semester" value="{{ old('semester', $pelanggarans->semester) }}"
                                readonly>
                            @error('semester')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="kelas_id" class="form-label">Kelas</label>
                            <select class="form-select @error('kelas_id') is-invalid @enderror" id="kelas_id"
                                name="kelas_id" disabled>
                                <option selected disabled>Pilih Kelas</option>
                                @foreach ($kelas as $kls)
                                    <option value="{{ $kls->id_kelas }}"
                                        {{ old('kelas_id', $pelanggarans->kelas_id) == $kls->id_kelas ? 'selected' : '' }}>
                                        {{ $kls->nama_kelas }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kelas_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="peringatan" class="form-label">Peringatan</label>
                            <select class="form-select @error('peringatan') is-invalid @enderror" id="peringatan"
                                name="peringatan" disabled>
                                <option selected disabled>Pilih jenis peringatan</option>
                                <option value="lisan"
                                    {{ old('peringatan', $pelanggarans->peringatan ?? '') == 'lisan' ? 'selected' : '' }}>
                                    Lisan</option>
                                <option value="tertulis"
                                    {{ old('peringatan', $pelanggarans->peringatan ?? '') == 'tertulis' ? 'selected' : '' }}>
                                    Tertulis</option>
                            </select>
                            @error('peringatan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="hari" class="form-label">Hari</label>
                            <input type="text" class="form-control @error('hari') is-invalid @enderror" id="hari"
                                name="hari" value="{{ old('hari', $pelanggarans->hari) }}" readonly>
                            @error('hari')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="tglSurat" class="form-label">Tanggal</label>
                            <input type="date" class="form-control @error('tglSurat') is-invalid @enderror"
                                id="tglSurat" name="tglSurat" value="{{ old('tglSurat', $pelanggarans->tglSurat) }}"
                                readonly>
                            @error('tglSurat')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="pasal" class="form-label">Pasal</label>
                            <input type="text" class="form-control @error('pasal') is-invalid @enderror"
                                id="pasal" name="pasal" value="{{ old('pasal', $pelanggarans->pasal) }}" readonly>
                            @error('pasal')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="isi_pasal" class="form-label">Isi Pasal</label>
                            <input type="text" class="form-control @error('isi_pasal') is-invalid @enderror"
                                id="isi_pasal" name="isi_pasal" value="{{ old('isi_pasal', $pelanggarans->isi_pasal) }}"
                                readonly>
                            @error('isi_pasal')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="ttd_mahasiswa" class="form-label">Upload Tanda Tangan Mahasiswa</label>
                            @if ($pelanggarans->ttd_mahasiswa)
                                <div class="mb-3">
                                    <img src="{{ asset('storage/' . $pelanggarans->ttd_mahasiswa) }}"
                                        alt="Tanda Tangan Mahasiswa" class="img-preview" width="100px">
                                </div>
                                <input type="hidden" name="original_ttd_mahasiswa"
                                    value="{{ $pelanggarans->ttd_mahasiswa }}">
                            @endif
                            <input type="file" class="form-control @error('ttd_mahasiswa') is-invalid @enderror"
                                id="ttd_mahasiswa" name="ttd_mahasiswa">
                            @error('ttd_mahasiswa')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <a href="/dashboard/mahasiswa/pelanggaran-akademik" class="btn btn-success"><i
                                class="bi bi-arrow-left-square"></i> Kembali</a>
                        <button type="button" class="btn btn-primary"
                            onclick="updatePelanggaranAkademik({{ $user->role_id }})">Ubah
                            Surat</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
