@extends('dashboard.mahasiswa.layouts.main')

@section('container')
    <div class="container-fluid mb-4 pt-4 px-4">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-6">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">Edit Surat Permohonan Pengunduran Diri</h6>
                    <form id="update-form-pengunduran" method="post" enctype="multipart/form-data"
                        action="/dashboard/mahasiswa/pengunduran-diri/{{ $pengundurans->noSurat }}">
                        @method('put')
                        @csrf
                        <input type="hidden" id="original_nama_mhs" value="{{ $pengundurans->nama_mhs }}">
                        <input type="hidden" id="original_nama_dosen_wali" value="{{ $pengundurans->nama_dosen_wali }}">
                        <input type="hidden" id="original_nama_ketua_jurusan"
                            value="{{ $pengundurans->nama_ketua_jurusan }}">
                        <input type="hidden" id="original_username" value="{{ $pengundurans->username }}">
                        <input type="hidden" id="original_semester" value="{{ $pengundurans->semester }}">
                        <input type="hidden" id="original_kelas" value="{{ $pengundurans->kelas_id }}">
                        <input type="hidden" id="original_jurusan" value="{{ $pengundurans->jurusan }}">
                        <input type="hidden" id="original_alamat" value="{{ $pengundurans->alamat }}">
                        <input type="hidden" id="original_tglSurat" value="{{ $pengundurans->tglSurat }}">
                        <input type="hidden" id="original_alasan" value="{{ $pengundurans->alasan }}">
                        <input type="hidden" id="original_no_telp" value="{{ $pengundurans->no_telp }}">
                        <div class="mb-3">
                            <label for="nama_mhs" class="form-label">Nama Pemohon</label>
                            <input type="text" class="form-control @error('nama_mhs') is-invalid @enderror"
                                id="nama_mhs" name="nama_mhs" value="{{ old('nama_mhs', $pengundurans->nama_mhs) }}">
                            @error('nama_mhs')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="nama_dosen_wali" class="form-label">Nama Wali Kelas</label>
                            <input type="text" class="form-control @error('nama_dosen_wali') is-invalid @enderror"
                                id="nama_dosen_wali" name="nama_dosen_wali"
                                value="{{ old('nama_dosen_wali', $pengundurans->nama_dosen_wali) }}">
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
                                value="{{ old('nama_ketua_jurusan', $pengundurans->nama_ketua_jurusan) }}">
                            @error('nama_ketua_jurusan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">NPM</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror"
                                id="username" name="username" value="{{ old('username', $pengundurans->username) }}">
                            @error('username')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="semester" class="form-label">Semester</label>
                            <input type="text" class="form-control @error('semester') is-invalid @enderror"
                                id="semester" name="semester" value="{{ old('semester', $pengundurans->semester) }}">
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
                                        {{ old('kelas_id', $pengundurans->kelas_id) == $kls->id_kelas ? 'selected' : '' }}>
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
                            <label for="jurusan" class="form-label">Jurusan</label>
                            <input type="text" class="form-control @error('jurusan') is-invalid @enderror"
                                id="jurusan" name="jurusan" value="{{ old('jurusan', $pengundurans->jurusan) }}">
                            @error('jurusan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="no_telp" class="form-label">No. Telp</label>
                            <input type="text" class="form-control @error('no_telp') is-invalid @enderror"
                                id="no_telp" name="no_telp" value="{{ old('no_telp', $pengundurans->no_telp) }}">
                            @error('no_telp')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat Lengkap</label>
                            <input type="text" class="form-control @error('alamat') is-invalid @enderror"
                                id="alamat" name="alamat" value="{{ old('alamat', $pengundurans->alamat) }}">
                            @error('alamat')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="alasan" class="form-label">Alasan Mengundurkan Diri</label>
                            <input type="text" class="form-control @error('alasan') is-invalid @enderror"
                                id="alasan" name="alasan" value="{{ old('alasan', $pengundurans->alasan) }}">
                            @error('alasan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="tglSurat" class="form-label">Tanggal</label>
                            <input type="date" class="form-control @error('tglSurat') is-invalid @enderror"
                                id="tglSurat" name="tglSurat" value="{{ old('tglSurat', $pengundurans->tglSurat) }}">
                            @error('tglSurat')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="ttd_mahasiswa" class="form-label">Upload Tanda Tangan Pemohon</label>
                            @if ($pengundurans->ttd_mahasiswa)
                                <div class="mb-3">
                                    <img src="{{ asset('storage/' . $pengundurans->ttd_mahasiswa) }}"
                                        alt="Tanda Tangan Mahasiswa" class="img-preview" width="100px">
                                </div>
                                <input type="hidden" name="original_ttd_mahasiswa"
                                    value="{{ $pengundurans->ttd_mahasiswa }}">
                            @endif
                            <input type="file" class="form-control @error('ttd_mahasiswa') is-invalid @enderror"
                                id="ttd_mahasiswa" name="ttd_mahasiswa">
                            @error('ttd_mahasiswa')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <a href="/dashboard/mahasiswa/pengunduran-diri" class="btn btn-success"><i
                                class="bi bi-arrow-left-square"></i> Kembali</a>
                        <button type="button" class="btn btn-primary"
                            onclick="updatePengunduranDiri({{ $user->role_id }})">Edit
                            Surat</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
