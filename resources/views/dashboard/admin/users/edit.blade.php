@extends('dashboard.admin.layouts.main')

@section('container')
    <div class="container-fluid mb-4 pt-4 px-4">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-6">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">Edit Kelas</h6>
                    <form id="update-form-user" method="post" action="/dashboard/admin/user/{{ $users->id }}">
                        @method('put')
                        @csrf
                        <input type="hidden" id="original_nama_pemilik" name="original_nama_pemilik"
                            value="{{ $users->nama_pemilik }}">
                        <input type="hidden" id="original_username" id="original_username" value="{{ $users->username }}">
                        <input type="hidden" id="original_no_telp" id="original_no_telp" value="{{ $users->no_telp }}">
                        <input type="hidden" id="original_email" id="original_email" value="{{ $users->email }}">
                        <input type="hidden" id="original_password" id="original_password" value="{{ $users->password }}">
                        <input type="hidden" id="original_role" id="original_role" value="{{ $users->role_id }}">
                        <input type="hidden" id="original_kelas" id="original_kelas" value="{{ $users->kelas_id }}">
                        <input type="hidden" id="original_jurusan" id="original_jurusan" value="{{ $users->jurusan }}">
                        <input type="hidden" id="original_perguruan_tinggi" id="original_perguruan_tinggi"
                            value="{{ $users->perguruan_tinggi }}">
                        <div class="mb-3">
                            <label for="nama_pemilik" class="form-label">Nama Pemilik</label>
                            <input type="text" class="form-control @error('nama_pemilik') is-invalid @enderror"
                                id="nama_pemilik" name="nama_pemilik"
                                value="{{ old('nama_pemilik', $users->nama_pemilik) }}">
                            @error('nama_pemilik')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror"
                                id="username" name="username" value="{{ old('username', $users->username) }}">
                            @error('username')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="no_telp" class="form-label">Nomor Telepon</label>
                            <input type="no_telp" class="form-control @error('no_telp') is-invalid @enderror" id="no_telp"
                                name="no_telp" value="{{ old('no_telp', $users->no_telp) }}">
                            @error('no_telp')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" value="{{ old('email', $users->email) }}">
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" placeholder="Masukkan password baru jika ingin mengganti">
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                                id="password_confirmation" name="password_confirmation">
                            @error('password_confirmation')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="role_id" class="form-label">Role</label>
                            <select class="form-select @error('role_id') is-invalid @enderror" id="role_id"
                                name="role_id">
                                <option selected disabled>Pilih Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id_role }}"
                                        {{ old('role_id', $users->role_id) == $role->id_role ? 'selected' : '' }}>
                                        {{ $role->nama_role }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role_id')
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
                                        {{ old('kelas_id', $users->kelas_id) == $kls->id_kelas ? 'selected' : '' }}>
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
                            <input type="text" class="form-control" id="jurusan" name="jurusan"
                                value="Komputer dan Bisnis">
                        </div>
                        <div class="mb-3">
                            <label for="perguruan_tinggi" class="form-label">Perguruan Tinggi</label>
                            <input type="text" class="form-control" id="perguruan_tinggi" name="perguruan_tinggi"
                                value="Politeknik Negeri Cilacap">
                        </div>
                        <a href="/dashboard/admin/user" class="btn btn-success"><i class="bi bi-arrow-left-square"></i>
                            Kembali</a>
                        <button type="button" class="btn btn-primary" onclick="updateUser()">Edit
                            User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
