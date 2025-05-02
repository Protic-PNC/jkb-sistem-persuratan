@extends('layouts.main')

@section('container')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <!-- Modern Card Design with Balanced Proportions -->
                <div class="card border-0" style="border-radius: 15px; box-shadow: 0 8px 25px rgba(0,0,0,0.06);">
                    <!-- Top Accent Bar -->
                    <div class="position-relative">
                        <div class="bg-primary" style="height: 5px; border-radius: 15px 15px 0 0;"></div>
                    </div>

                    <div class="card-body p-4">
                        <h6 class="mb-4 text-center">Edit Akun</h6>

                        <form id="update-form-user" method="post" action="/profile/{{ $users->id }}"
                            enctype="multipart/form-data">
                            @method('put')
                            @csrf
                            <input type="hidden" id="original_email" value="{{ $users->email }}">
                            <input type="hidden" id="original_password" value="{{ $users->password }}">
                            <input type="hidden" id="original_profile_picture" value="{{ $users->profile_picture }}">

                            <!-- Profile Picture with Appropriately Sized Circle Design -->
                            <div class="mb-4 text-center">
                                <div class="position-relative d-inline-block mb-3">
                                    @if ($users->profile_picture)
                                        <div class="rounded-circle overflow-hidden"
                                            style="width: 100px; height: 100px; border: 2px solid #f8f9fa;">
                                            <img src="{{ asset('storage/profile_pictures/' . $users->profile_picture) }}"
                                                alt="Profile Picture" class="w-100 h-100" style="object-fit: cover;">
                                        </div>
                                    @else
                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                            style="width: 100px; height: 100px; border: 2px solid #f8f9fa;">
                                            <i class="bi bi-person text-secondary" style="font-size: 2.5rem;"></i>
                                        </div>
                                    @endif
                                    <label for="profile_picture"
                                        class="position-absolute bottom-0 end-0 bg-white rounded-circle p-1 shadow-sm"
                                        style="cursor: pointer; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-camera" style="font-size: 0.9rem;"></i>
                                    </label>
                                </div>

                                <input type="file"
                                    class="form-control d-none @error('profile_picture') is-invalid @enderror"
                                    id="profile_picture" name="profile_picture">
                                @error('profile_picture')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Styled Form Sections with Appropriate Sizing -->
                            <div class="p-3 bg-light rounded-3 mb-3">
                                <div class="text-muted mb-3 small"><i class="bi bi-person-badge me-1"></i>Informasi Kontak
                                </div>


                                <!-- Email -->
                                <div class="mb-0">
                                    <label for="email" class="form-label small">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="bi bi-envelope text-primary small"></i>
                                        </span>
                                        <input type="email"
                                            class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email', $users->email) }}"
                                            style="border-top-right-radius: 6px; border-bottom-right-radius: 6px;">
                                    </div>
                                    @error('email')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Password Section -->
                            <div class="p-3 bg-light rounded-3 mb-3">
                                <div class="text-muted mb-3 small"><i class="bi bi-shield-lock me-1"></i>Pengaturan Password
                                </div>

                                <!-- Current Password -->
                                <div class="mb-3">
                                    <label for="current_password" class="form-label small">Password Saat Ini</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="bi bi-key text-primary small"></i>
                                        </span>
                                        <input type="password"
                                            class="form-control border-start-0 ps-0 @error('current_password') is-invalid @enderror"
                                            id="current_password" name="current_password"
                                            style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                                        <button class="btn btn-outline-secondary toggle-password py-0" type="button"
                                            data-target="current_password"
                                            style="border-top-right-radius: 6px; border-bottom-right-radius: 6px;">
                                            <i class="bi bi-eye small"></i>
                                        </button>
                                    </div>
                                    @error('current_password')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- New Password -->
                                <div class="mb-3">
                                    <label for="password" class="form-label small">Password Baru</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="bi bi-lock text-primary small"></i>
                                        </span>
                                        <input type="password"
                                            class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror"
                                            id="password" name="password"
                                            placeholder="Masukkan password baru jika ingin mengganti"
                                            style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                                        <button class="btn btn-outline-secondary toggle-password py-0" type="button"
                                            data-target="password"
                                            style="border-top-right-radius: 6px; border-bottom-right-radius: 6px;">
                                            <i class="bi bi-eye small"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Confirm Password -->
                                <div class="mb-0">
                                    <label for="password_confirmation" class="form-label small">Konfirmasi
                                        Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="bi bi-lock-fill text-primary small"></i>
                                        </span>
                                        <input type="password"
                                            class="form-control border-start-0 ps-0 @error('password_confirmation') is-invalid @enderror"
                                            id="password_confirmation" name="password_confirmation"
                                            style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                                        <button class="btn btn-outline-secondary toggle-password py-0" type="button"
                                            data-target="password_confirmation"
                                            style="border-top-right-radius: 6px; border-bottom-right-radius: 6px;">
                                            <i class="bi bi-eye small"></i>
                                        </button>
                                    </div>
                                    @error('password_confirmation')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex gap-2 justify-content-between mt-3">
                                <a href="/profile" class="btn btn-success">
                                    <i class="bi bi-arrow-left-square me-1"></i> Kembali
                                </a>
                                <button type="button" class="btn btn-primary" onclick="updateProfile()">
                                    <i class="bi bi-check2 me-1"></i> Ubah Akun
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
