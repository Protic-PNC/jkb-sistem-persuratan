@extends('layouts.main')

@section('container')
    <div class="row justify-content-center mt-5 mb-5">
        <div class="col-md-3">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4">
                    @if (session()->has('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session()->has('loginError'))
                        <div id="loginError" data-message="{{ session('loginError') }}"></div>
                    @endif

                    <h1 class="h3 mb-3 fw-normal text-center">Silahkan Login</h1>
                    <form action="/login" method="post">
                        @csrf
                        <div class="form-floating mb-3">
                            <input type="text" name="username"
                                class="form-control @error('username') is-invalid @enderror" id="username"
                                placeholder="Username" autofocus required value="{{ old('username') }}">
                            <label for="username">Username</label>
                            @error('username')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Password Input -->
                        <div class="form-floating mb-3 position-relative">
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror" id="password"
                                placeholder="Password" required>
                            <label for="password">Password</label>
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <!-- Mata Icon -->
                            <span id="toggle-password"
                                style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); cursor: pointer;">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>

                        <!-- Login Button -->
                        <button class="w-100 btn btn-lg btn-primary mt-3" type="submit">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
