@extends('layouts.main')

@section('container')
    <div class="container mb-5 mt-5">
        <h1 class="mb-4 text-center">Profile</h1>
        <div class="row mt-4 justify-content-center">
            <div class="col-md-8">
                <div class="card p-4 rounded shadow" style="border-radius: 15px; background: #f8f9fa;">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <div class="profile-picture">
                                <img src="{{ $user->profile_picture ? asset('storage/profile_pictures/' . $user->profile_picture) : asset('img/default-profile.jpg') }}"
                                    alt="Foto Profil" class="img-fluid rounded-circle"
                                    style="width: 150px; height: 150px; transition: transform 0.2s;">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h4 class="mb-2">{{ $user->nama_pemilik }}</h4>
                            <p class="mb-1"><strong>Username:</strong> {{ $user->username }}</p>
                            <p class="mb-1"><strong>No Telp:</strong> {{ $user->no_telp }}</p>
                            <p class="mb-1"><strong>Email:</strong> {{ $user->email }}</p>
                            <p class="mb-1"><strong>Jabatan:</strong> {{ $user->role->nama_role }}</p>
                            <p class="mb-1"><strong>Kelas:</strong> {{ $user->kelas->nama_kelas ?? '-' }}</p>
                            <a href="/profile/{{ $user->id }}/edit" class="btn btn-primary mt-3">Edit</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .profile-picture:hover img {
            transform: scale(1.1);
        }
    </style>
@endsection
