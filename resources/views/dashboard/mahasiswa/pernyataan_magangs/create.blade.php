@extends('dashboard.mahasiswa.layouts.main')

@section('container')
    <div class="container-fluid mb-4 pt-4 px-4">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-6">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">Buat Surat Pernyataan Magang</h6>
                    <form id="create-form-magang" method="post" action="/dashboard/mahasiswa/pernyataan-magang">
                        @csrf
                        <div class="mb-3">
                            <label for="nama_ortu" class="form-label">Nama Orang Tua/Wali</label>
                            <input type="text" class="form-control @error('nama_ortu') is-invalid @enderror"
                                id="nama_ortu" name="nama_ortu" value="{{ old('nama_ortu') }}">
                            @error('nama_ortu')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <input type="text" class="form-control @error('alamat') is-invalid @enderror" id="alamat"
                                name="alamat" value="{{ old('alamat') }}">
                            @error('alamat')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="no_telp" class="form-label">No. Telp</label>
                            <input type="text" class="form-control @error('no_telp') is-invalid @enderror" id="no_telp"
                                name="no_telp" value="{{ old('no_telp') }}">
                            @error('no_telp')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="nama_mhs" class="form-label">Nama Mahasiswa</label>
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
                            <label for="jurusan" class="form-label">Jurusan</label>
                            <input type="text" class="form-control @error('jurusan') is-invalid @enderror" id="jurusan"
                                name="jurusan" value="{{ auth()->user()->jurusan ?? old('jurusan') }}">
                            @error('jurusan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="perguruan_tinggi" class="form-label">Perguruan Tinggi</label>
                            <input type="text" class="form-control @error('perguruan_tinggi') is-invalid @enderror"
                                id="perguruan_tinggi" name="perguruan_tinggi"
                                value="{{ auth()->user()->perguruan_tinggi ?? old('perguruan_tinggi') }}">
                            @error('perguruan_tinggi')
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
                        <a href="/dashboard/mahasiswa/pernyataan-magang" class="btn btn-success"><i
                                class="bi bi-arrow-left-square"></i> Kembali</a>
                        <button type="button" class="btn btn-primary" onclick="savePernyataanMagang()">Buat Surat</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
