<!-- Sidebar Start -->
<div class="sidebar pe-4 pb-3">
    <nav class="navbar bg-light navbar-light">
        <a class="navbar-brand mx-4 mb-3">
            <h3><img width="35px" style="margin-right: 10px; color: #2d3748;" src="{{ asset('img/jkb_logo.png') }}"
                    alt="logo jkb"> Arsip Surat
            </h3>
        </a>
        <div class="d-flex align-items-center ms-4 mb-4">
            <div class="position-relative">
                <img class="rounded-circle"
                    src="{{ auth()->user()->profile_picture
                        ? asset('storage/profile_pictures/' . auth()->user()->profile_picture)
                        : asset('dashmin/img/user.png') }}"
                    alt="" style="width: 40px; height: 40px;">
                <div
                    class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1">
                </div>
            </div>
            <div class="ms-3">
                <h6 class="mb-0">{{ auth()->user()->nama_pemilik }}</h6>
                <span>{{ auth()->user()->username }}</span>
            </div>
        </div>
        <div class="navbar-nav w-100">
            <a href="/dashboard/dosen-wali" class="nav-item nav-link {{ Request::is('dashboard') ? 'active' : '' }}"><i
                    class='fas fa-clipboard-list me-2'></i>Dashboard</a>
            <div class="nav-item dropdown">
                <a href="#"
                    class="nav-link dropdown-toggle {{ Request::is('pelanggarans') || Request::is('pengundurans') ? 'active' : '' }}"
                    data-bs-toggle="dropdown"><i class="far fa-file-alt me-2"></i>Surat</a>
                <div class="dropdown-menu bg-transparent border-0">
                    <a href="/dashboard/dosen-wali/pelanggaran-akademik"
                        class="dropdown-item {{ Request::is('dashboard/dosen-wali/pelanggaran-akademik*') ? 'active' : '' }}">
                        Surat Pelanggaran Peraturan Akademik</a>
                    <a href="/dashboard/dosen-wali/pengunduran-diri"
                        class="dropdown-item {{ Request::is('dashboard/dosen-wali/pengunduran-diri*') ? 'active' : '' }}">
                        Surat Pengunduran
                        Diri</a>
                </div>
            </div>
        </div>
    </nav>
</div>
<!-- Sidebar End -->
