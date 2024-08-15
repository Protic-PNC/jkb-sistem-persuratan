@extends('dashboard.mahasiswa.layouts.main')

@section('container')
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-6">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">Edit Surat Pernyataan Magang</h6>
                    <form method="post" action="/dashboard/mahasiswa/pernyataan-magang/{{ $pernyataans->noSurat }}">
                        @method('put')
                        @csrf
                        <div class="mb-3">
                            <label for="nama_ortu" class="form-label">Nama Orang Tua/Wali</label>
                            <input type="text" class="form-control @error('nama_ortu') is-invalid @enderror" id="nama_ortu" name="nama_ortu" required value="{{ old('nama_ortu', $pernyataans->nama_ortu) }}">
                            @error('nama_ortu')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <input type="text" class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" required value="{{ old('alamat', $pernyataans->alamat) }}">
                            @error('alamat')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="no_telp" class="form-label">No. Telp</label>
                            <input type="text" class="form-control @error('no_telp') is-invalid @enderror" id="no_telp" name="no_telp" required value="{{ old('no_telp', $pernyataans->no_telp) }}">
                            @error('no_telp')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="nama_mhs" class="form-label">Nama Mahasiswa</label>
                            <input type="text" class="form-control @error('nama_mhs') is-invalid @enderror" id="nama_mhs" name="nama_mhs" required value="{{ auth()->user()->name ?? old('nama_mhs') }}" readonly>
                            @error('nama_mhs')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="npm" class="form-label">NPM</label>
                            <input type="text" class="form-control @error('npm') is-invalid @enderror" id="npm" name="npm" required value="{{ auth()->user()->npm ?? old('npm') }}" readonly>
                            @error('npm')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="jurusan" class="form-label">Jurusan</label>
                            <input type="text" class="form-control @error('jurusan') is-invalid @enderror" id="jurusan" name="jurusan" required value="{{ auth()->user()->jurusan ?? old('jurusan') }}" readonly>
                            @error('jurusan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="perguruan_tinggi" class="form-label">Perguruan Tinggi</label>
                            <input type="text" class="form-control @error('perguruan_tinggi') is-invalid @enderror" id="perguruan_tinggi" name="perguruan_tinggi" required value="{{ auth()->user()->perguruan_tinggi ?? old('perguruan_tinggi') }}" readonly>
                            @error('perguruan_tinggi')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="tglSurat" class="form-label">Tanggal</label>
                            <input type="date" class="form-control @error('tglSurat') is-invalid @enderror" id="tglSurat" name="tglSurat" required value="{{ old('tglSurat', $pernyataans->tglSurat) }}">
                            @error('tglSurat')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                         <a href="/dashboard/mahasiswa/pernyataan-magang" class="btn btn-success"><i class="bi bi-arrow-left-square"></i> Kembali</a>
                        <button type="submit" class="btn btn-primary">Edit Surat</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


