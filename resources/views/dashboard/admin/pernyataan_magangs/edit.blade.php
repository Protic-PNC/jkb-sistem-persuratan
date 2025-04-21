@extends('dashboard.admin.layouts.main')

@section('container')
    <div class="container-fluid mb-4 pt-4 px-4">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-6">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">Edit Surat Pernyataan Magang</h6>
                    <form id="update-form-magang" method="post" action="/dashboard/admin/pernyataan-magang/{{ $pernyataans->noSurat }}">
                        @method('put')
                        @csrf
                        <input type="hidden" id="original_nama_ortu" name="original_nama_ortu" value="{{ $pernyataans->nama_ortu }}">
                        <input type="hidden" id="original_alamat" name="original_alamat" value="{{ $pernyataans->alamat }}">
                        <input type="hidden" id="original_no_telp" name="original_no_telp" value="{{ $pernyataans->no_telp }}">
                        <input type="hidden" id="original_nama_mhs" name="original_nama_mhs" value="{{ $pernyataans->nama_mhs }}">
                        <input type="hidden" id="original_username" name="original_username" value="{{ $pernyataans->username }}">
                        <input type="hidden" id="original_jurusan" name="original_jurusan" value="{{ $pernyataans->jurusan }}">
                        <input type="hidden" id="original_perguruan_tinggi" name="original_perguruan_tinggi" value="{{ $pernyataans->perguruan_tinggi }}">
                        <input type="hidden" id="original_tglSurat" name="original_tglSurat" value="{{ $pernyataans->tglSurat }}">
                        <div class="mb-3">
                            <label for="nama_ortu" class="form-label">Nama Orang Tua/Wali</label>
                            <input type="text" class="form-control @error('nama_ortu') is-invalid @enderror" id="nama_ortu" name="nama_ortu" value="{{ old('nama_ortu', $pernyataans->nama_ortu) }}">
                            @error('nama_ortu')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <input type="text" class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" value="{{ old('alamat', $pernyataans->alamat) }}">
                            @error('alamat')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="no_telp" class="form-label">No. Telp</label>
                            <input type="text" class="form-control @error('no_telp') is-invalid @enderror" id="no_telp" name="no_telp" value="{{ old('no_telp', $pernyataans->no_telp) }}">
                            @error('no_telp')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="nama_mhs" class="form-label">Nama Mahasiswa</label>
                            <input type="text" class="form-control @error('nama_mhs') is-invalid @enderror" id="nama_mhs" name="nama_mhs" value="{{ old('nama_mhs', $pernyataans->nama_mhs) }}">
                            @error('nama_mhs')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">NPM</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username', $pernyataans->username) }}">
                            @error('username')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="jurusan" class="form-label">Jurusan</label>
                            <input type="text" class="form-control @error('jurusan') is-invalid @enderror" id="jurusan" name="jurusan" value="{{ old('jurusan', $pernyataans->jurusan) }}" readonly>
                            @error('jurusan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="perguruan_tinggi" class="form-label">Perguruan Tinggi</label>
                            <input type="text" class="form-control @error('perguruan_tinggi') is-invalid @enderror" id="perguruan_tinggi" name="perguruan_tinggi" value="{{ old('perguruan_tinggi', $pernyataans->perguruan_tinggi) }}" readonly>
                            @error('perguruan_tinggi')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="tglSurat" class="form-label">Tanggal</label>
                            <input type="date" class="form-control @error('tglSurat') is-invalid @enderror" id="tglSurat" name="tglSurat" value="{{ old('tglSurat', $pernyataans->tglSurat) }}">
                            @error('tglSurat')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <a href="/dashboard/admin/pernyataan-magang" class="btn btn-success"><i class="bi bi-arrow-left-square"></i> Kembali</a>
                        <button type="button" class="btn btn-primary" onclick="updatePernyataanMagang()">Edit Surat</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


