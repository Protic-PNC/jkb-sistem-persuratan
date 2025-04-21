@extends('dashboard.admin.layouts.main')

@section('container')
    <div class="container-fluid mb-4 pt-4 px-4">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-6">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">Buat Surat Peringatan karena Pelanggaran Peraturan Akademik</h6>
                    <form id="create-form-pelanggaran" method="post" action="/dashboard/dosen-wali/pelanggaran-akademik"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="nama_pelapor" class="form-label">Nama Pelapor</label>
                            <input type="text" class="form-control @error('nama_pelapor') is-invalid @enderror"
                                id="nama_pelapor" name="nama_pelapor"
                                value="{{ old('nama_pelapor', Auth::user()->nama_pemilik) }}">
                            @error('nama_pelapor')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="nama_dosen_wali" class="form-label">Nama Dosen Wali</label>
                            <input type="text" class="form-control @error('nama_dosen_wali') is-invalid @enderror"
                                id="nama_dosen_wali" name="nama_dosen_wali" value="{{ old('nama_dosen_wali') }}"
                                placeholder="Field ini terisi otomatis berdasarkan kelas yang dipilih">
                            @error('nama_dosen_wali')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="nama_ketua_jurusan" class="form-label">Nama Ketua Jurusan</label>
                            <input type="text" class="form-control @error('nama_ketua_jurusan') is-invalid @enderror"
                                id="nama_ketua_jurusan" name="nama_ketua_jurusan" value="{{ $user->nama_pemilik }}">
                            @error('nama_ketua_jurusan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="nama_mhs" class="form-label">Kepada</label>
                            <input type="text" class="form-control @error('nama_mhs') is-invalid @enderror"
                                id="nama_mhs" name="nama_mhs" value="{{ old('nama_mhs') }}"
                                placeholder="Field ini terisi otomatis berdasarkan NPM">
                            @error('nama_mhs')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">NPM</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror"
                                id="username" name="username" value="{{ old('username') }}">
                            @error('username')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="semester" class="form-label">Semester</label>
                            <input type="text" class="form-control @error('semester') is-invalid @enderror"
                                id="semester" name="semester"
                                value="{{ old('semester', $pelanggarans->semester ?? '') }}">
                            @error('semester')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="kelas_id" class="form-label">Kelas</label>
                            <select class="form-select @error('kelas_id') is-invalid @enderror" id="kelas_id"
                                name="kelas_id">
                                <option selected disabled>Pilih Kelas</option>
                                @foreach ($kelas as $kls)
                                    <option value="{{ $kls->id_kelas }}"
                                        {{ old('kelas_id') == $kls->id_kelas ? 'selected' : '' }}>
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
                                name="peringatan">
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
                                name="hari" value="{{ old('hari') }}"
                                placeholder="Field ini terisi otomatis berdasarkan tanggal yang dipilih">
                            @error('hari')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="tglSurat" class="form-label">Tanggal</label>
                            <input type="date" class="form-control @error('tglSurat') is-invalid @enderror"
                                id="tglSurat" name="tglSurat" value="{{ old('tglSurat') }}">
                            @error('tglSurat')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="pasal" class="form-label">Pasal</label>
                            <input type="text" class="form-control @error('pasal') is-invalid @enderror"
                                id="pasal" name="pasal" value="{{ old('pasal') }}">
                            @error('pasal')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="isi_pasal" class="form-label">Isi Pasal</label>
                            <input type="text" class="form-control @error('isi_pasal') is-invalid @enderror"
                                id="isi_pasal" name="isi_pasal" value="{{ old('isi_pasal') }}">
                            @error('isi_pasal')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="ttd_pelapor" class="form-label">Upload Tanda Tangan Pelapor</label>
                            <input type="file" class="form-control @error('ttd_pelapor') is-invalid @enderror"
                                id="ttd_pelapor" name="ttd_pelapor">
                            @error('ttd_pelapor')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <a href="/dashboard/dosen-wali/pelanggaran-akademik" class="btn btn-success"><i
                                class="bi bi-arrow-left-square"></i> Kembali</a>
                        <button type="button" class="btn btn-primary"
                            onclick="savePelanggaranAkademik({{ Auth::user()->role_id }})">Buat
                            Surat</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
