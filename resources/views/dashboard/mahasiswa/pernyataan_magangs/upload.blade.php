@extends('dashboard.mahasiswa.layouts.main')

@section('container')
<div class="container pt-4 px-4 d-flex justify-content-center">
    <div class="bg-light rounded p-4" style="max-width: 600px; width: 100%;">
        <h4 class="mb-4">Upload File PDF untuk Surat: {{ $pernyataans->noSurat }}</h4>

        @if(session()->has('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session()->has('info'))
            <div class="alert alert-info">{{ session('info') }}</div>
        @endif

        <form id="upload-magang-form" action="{{ url("/dashboard/mahasiswa/pernyataan-magang/{$pernyataans->noSurat}/upload") }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="file_pdf" class="form-label">Pilih File PDF</label>
                <input type="file" name="file_pdf" class="form-control" required>
                @error('file_pdf')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <button type="button" class="btn btn-primary" onclick="uploadMahasiswaSuratMagang()">Upload</button>
            <a href="/dashboard/mahasiswa/pernyataan-magang" class="btn btn-success">Kembali</a>
        </form>
    </div>
</div>
@endsection
