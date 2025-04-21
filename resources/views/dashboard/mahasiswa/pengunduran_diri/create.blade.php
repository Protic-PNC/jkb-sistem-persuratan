@extends('dashboard.mahasiswa.layouts.main')

@section('container')
    <div class="container-fluid mb-4 pt-4 px-4">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-6">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">Buat Surat Permohonan Pengunduran Diri</h6>
                    <form id="create-form-pengunduran" method="post" action="/dashboard/mahasiswa/pengunduran-diri"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="nama_mhs" class="form-label">Nama Pemohon</label>
                            <input type="text" class="form-control @error('nama_mhs') is-invalid @enderror"
                                id="nama_mhs" name="nama_mhs"
                                value="{{ auth()->user()->nama_pemilik ?? old('nama_mhs') }}">
                            @error('nama_mhs')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="nama_dosen_wali" class="form-label">Nama Dosen Wali</label>
                            <input type="text" class="form-control @error('nama_dosen_wali') is-invalid @enderror"
                                id="nama_dosen_wali" name="nama_dosen_wali" value="{{ old('nama_dosen_wali') }}">
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
                            <label for="username" class="form-label">NPM</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror"
                                id="username" name="username" value="{{ auth()->user()->username ?? old('username') }}">
                            @error('username')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="semester" class="form-label">Semester</label>
                            <input type="text" class="form-control @error('semester') is-invalid @enderror"
                                id="semester" name="semester" value="{{ auth()->user()->semester ?? old('semester') }}">
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
                            <label for="jurusan" class="form-label">Jurusan</label>
                            <input type="text" class="form-control @error('jurusan') is-invalid @enderror" id="jurusan"
                                name="jurusan" value="{{ auth()->user()->jurusan ?? old('jurusan') }}" readonly>
                            @error('jurusan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="no_telp" class="form-label">No. Telp</label>
                            <input type="text" class="form-control @error('no_telp') is-invalid @enderror" id="no_telp"
                                name="no_telp" value="{{ auth()->user()->no_telp ?? old('no_telp') }}">
                            @error('no_telp')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat Lengkap</label>
                            <input type="text" class="form-control @error('alamat') is-invalid @enderror" id="alamat"
                                name="alamat" value="{{ old('alamat') }}">
                            @error('alamat')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="alasan" class="form-label">Alasan Mengundurkan Diri</label>
                            <input type="text" class="form-control @error('alasan') is-invalid @enderror" id="alasan"
                                name="alasan" value="{{ old('alasan') }}">
                            @error('alasan')
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

                        <!-- Upload TTD Mahasiswa -->
                        <div class="mb-3">
                            <label for="ttd_mahasiswa" class="form-label">Upload Tanda Tangan Pemohon</label>
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
                            onclick="savePengunduranDiri({{ $user->role_id }})">Buat Surat</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
