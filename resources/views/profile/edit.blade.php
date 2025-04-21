@extends('layouts.main')

@section('container')
    <div class="container d-flex justify-content-center align-items-center mb-4 pt-4 px-4" style="min-height: 100vh;">
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-body">
                    <h6 class="mb-4 text-center">Edit Akun</h6>
                    <form id="update-form-user" method="post" action="/profile/{{ $users->id }}"
                        enctype="multipart/form-data">
                        @method('put')
                        @csrf
                        <input type="hidden" id="original_no_telp" value="{{ $users->no_telp }}">
                        <input type="hidden" id="original_email" value="{{ $users->email }}">
                        <input type="hidden" id="original_password" value="{{ $users->password }}">
                        <input type="hidden" id="original_profile_picture" value="{{ $users->profile_picture }}">

                        <!-- Profile Picture -->
                        <div class="mb-3">
                            <label for="profile_picture" class="form-label">Foto Profil</label>
                            <input type="file" class="form-control @error('profile_picture') is-invalid @enderror"
                                id="profile_picture" name="profile_picture">
                            @error('profile_picture')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if ($users->profile_picture)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/profile_pictures/' . $users->profile_picture) }}"
                                        alt="Profile Picture" class="img-thumbnail" width="100">
                                </div>
                            @endif
                        </div>

                        <!-- Contact Information -->
                        <div class="mb-3">
                            <label for="no_telp" class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control @error('no_telp') is-invalid @enderror" id="no_telp"
                                name="no_telp" value="{{ old('no_telp', $users->no_telp) }}">
                            @error('no_telp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" value="{{ old('email', $users->email) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password Fields -->
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Password Saat Ini</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                id="current_password" name="current_password">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" placeholder="Masukkan password baru jika ingin mengganti">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                                id="password_confirmation" name="password_confirmation">
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <a href="/profile" class="btn btn-success"><i class="bi bi-arrow-left-square"></i> Kembali</a>
                        <button type="button" class="btn btn-primary" onclick="updateProfile()">Edit
                            User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
