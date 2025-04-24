@extends('layouts.main')

@section('container')
    <div class="container mb-5 mt-5">
        <h1 class="mb-4 text-center">Profile</h1>
        <div class="row mt-4 justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg border-0 overflow-hidden" style="border-radius: 20px; transition: all 0.3s ease;">
                    <div class="bg-primary" style="height: 5px;"></div>

                    <div class="card-body p-0">
                        <div class="position-absolute top-0 end-0 d-none d-md-block">
                            <svg width="150" height="150" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg"
                                style="opacity: 0.03">
                                <path fill="currentColor"
                                    d="M44.3,-76.4C58.8,-69.8,72.8,-60.4,81.4,-46.9C90,-33.4,93.1,-16.7,92.1,-0.6C91.1,15.6,85.9,31.2,76.8,44.3C67.8,57.4,54.9,68,40.5,75.5C26.2,83,13.1,87.4,-0.6,88.5C-14.3,89.6,-28.5,87.3,-41.9,81.1C-55.2,74.8,-67.6,64.5,-75.8,51.1C-84,37.6,-88,20.8,-88.9,3.6C-89.8,-13.7,-87.7,-27.3,-81,-39.2C-74.3,-51,-63,-61,-49.9,-67.4C-36.7,-73.8,-21.7,-76.7,-6.5,-74.7C8.7,-72.7,29.9,-83,44.3,-76.4Z"
                                    transform="translate(100 100)" />
                            </svg>
                        </div>

                        <div class="p-4 p-md-5">
                            <div class="row align-items-center">
                                <!-- Profile Picture Section with Animation -->
                                <div class="col-md-4 text-center mb-4 mb-md-0">
                                    <div style="width: 150px; height: 150px; margin: 0 auto; position: relative;"
                                        class="profile-picture">
                                        <div class="rounded-circle p-1 bg-white shadow"
                                            style="width: 150px; height: 150px;">
                                            <div class="rounded-circle overflow-hidden h-100 w-100">
                                                <img src="{{ $user->profile_picture ? asset('storage/profile_pictures/' . $user->profile_picture) : asset('img/default-profile.jpg') }}"
                                                    alt="Foto Profil"
                                                    style="object-fit: cover; transition: transform 0.3s ease; width: 100%; height: 100%;">
                                            </div>
                                        </div>
                                        <div class="position-absolute bottom-0 end-0 bg-success rounded-circle p-2 border border-white"
                                            style="width: 20px; height: 20px;"></div>
                                    </div>
                                </div>

                                <!-- User Information Section -->
                                <div class="col-md-8">
                                    <div class="bg-light p-4 rounded-3">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h4 class="mb-0" style="font-weight: 600;">{{ $user->nama_pemilik }}</h4>
                                            <span class="badge bg-primary">{{ $user->role->nama_role }}</span>
                                        </div>

                                        <div>
                                            <div style="display: flex; align-items: center; margin-bottom: 0.8rem;">
                                                <i class="bi bi-person text-primary me-2" style="width: 24px;"></i>
                                                <strong style="min-width: 80px; margin-right: 1rem;">Username:</strong>
                                                <span>{{ $user->username }}</span>
                                            </div>

                                            <div style="display: flex; align-items: center; margin-bottom: 0.8rem;">
                                                <i class="bi bi-telephone text-primary me-2" style="width: 24px;"></i>
                                                <strong style="min-width: 80px; margin-right: 1rem;">No Telp:</strong>
                                                <span>{{ $user->no_telp }}</span>
                                            </div>

                                            <div style="display: flex; align-items: center; margin-bottom: 0.8rem;">
                                                <i class="bi bi-envelope text-primary me-2" style="width: 24px;"></i>
                                                <strong style="min-width: 80px; margin-right: 1rem;">Email:</strong>
                                                <span>{{ $user->email }}</span>
                                            </div>

                                            <div style="display: flex; align-items: center; margin-bottom: 0.8rem;">
                                                <i class="bi bi-mortarboard text-primary me-2" style="width: 24px;"></i>
                                                <strong style="min-width: 80px; margin-right: 1rem;">Kelas:</strong>
                                                <span>{{ $user->kelas->nama_kelas ?? '-' }}</span>
                                            </div>
                                        </div>

                                        <div class="mt-3 d-grid d-md-flex justify-content-md-end">
                                            <a href="/profile/{{ $user->id }}/edit" class="btn btn-primary">
                                                <i class="bi bi-pencil-square me-2"></i>Edit
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
