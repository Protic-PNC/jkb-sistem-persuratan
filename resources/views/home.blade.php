@extends('layouts.main')

@section('container')
    <!-- Header Start -->
    <div class="container-fluid hero-header bg-light py-5" style="padding-top: 3rem; padding-bottom: 3rem;">
        <div class="container" style="padding-top: 3rem; padding-bottom: 3rem;">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 mb-3 animated slideInDown"
                        style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                        Website Persuratan Mahasiswa Jurusan Komputer dan Bisnis Politeknik Negeri Cilacap
                    </h1>
                    <a href="/login" class="btn btn-primary btn-lg py-3 px-4 animated slideInDown"
                        style="transition: background-color 0.3s ease-in-out, border-color 0.3s ease-in-out;">
                        Mulai Membuat Surat
                    </a>
                </div>
                <div class="col-lg-6 animated fadeIn">
                    <img class="img-fluid animated pulse infinite" style="animation-duration: 3s;" src="img/heroWeb.png"
                        alt="">
                </div>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <!-- Services Section Start -->
    <div class="container py-5">
        <div class="row g-4 text-center">
            <div class="col-12">
                <h2 class="mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 700;">Layanan Sistem Persuratan
                    Mahasiswa</h2>
                <p class="lead mb-5">
                    Sistem ini mengelola empat jenis surat yang dapat digunakan untuk kegiatan akademik<br>
                    di Jurusan Komputer dan Bisnis Politeknik Negeri Cilacap
                </p>
            </div>
        </div>
        <div class="row g-4">
            <!-- Card 1 -->
            <div class="col-md-6">
                <div class="service-card rounded-4 p-4 text-center bg-white d-flex flex-column position-relative overflow-hidden"
                    style="transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); height: 100%; border: none; box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05); transform-origin: center; opacity: 0;"
                    onmouseover="this.style.transform='translateY(-8px) scale(1.02)'; this.style.boxShadow='0 15px 30px rgba(78, 115, 223, 0.15)';"
                    onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='0 10px 20px rgba(0, 0, 0, 0.05)';">
                    <div class="card-decoration"
                        style="position: absolute; top: 0; left: 0; width: 100%; height: 5px; background: linear-gradient(90deg, #4e73df, #36b9cc); transition: width 0.3s ease-in-out;"
                        onmouseover="this.style.width='105%'" onmouseout="this.style.width='100%'"></div>
                    <div class="icon-container d-flex align-items-center justify-content-center mx-auto mb-4"
                        style="width: 90px; height: 90px; background-color: rgba(78, 115, 223, 0.1); border-radius: 50%; transition: all 0.3s ease;">
                        <i class="fas fa-briefcase fa-3x text-primary" style="transition: transform 0.3s ease;"></i>
                    </div>
                    <h4 class="mb-3" style="font-weight: 600; color: #333; transition: color 0.3s ease;">Surat Pernyataan
                        Magang Industri</h4>
                    <p class="text-muted">Surat ini digunakan untuk menyatakan kesiapan mahasiswa melaksanakan magang
                        industri pada periode
                        tertentu.</p>
                </div>
            </div>
            <!-- Card 2 -->
            <div class="col-md-6">
                <div class="service-card rounded-4 p-4 text-center bg-white d-flex flex-column position-relative overflow-hidden"
                    style="transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); height: 100%; border: none; box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05); transform-origin: center; opacity: 0;"
                    onmouseover="this.style.transform='translateY(-8px) scale(1.02)'; this.style.boxShadow='0 15px 30px rgba(231, 74, 59, 0.15)';"
                    onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='0 10px 20px rgba(0, 0, 0, 0.05)';">
                    <div class="card-decoration"
                        style="position: absolute; top: 0; left: 0; width: 100%; height: 5px; background: linear-gradient(90deg, #e74a3b, #f6c23e); transition: width 0.3s ease-in-out;"
                        onmouseover="this.style.width='105%'" onmouseout="this.style.width='100%'"></div>
                    <div class="icon-container d-flex align-items-center justify-content-center mx-auto mb-4"
                        style="width: 90px; height: 90px; background-color: rgba(231, 74, 59, 0.1); border-radius: 50%; transition: all 0.3s ease;">
                        <i class="fas fa-sign-out-alt fa-3x" style="color: #e74a3b; transition: transform 0.3s ease;"></i>
                    </div>
                    <h4 class="mb-3" style="font-weight: 600; color: #333; transition: color 0.3s ease;">Surat Permohonan
                        Pengunduran Diri</h4>
                    <p class="text-muted">Surat ini digunakan oleh mahasiswa yang ingin mengajukan pengunduran diri dari
                        program studi pada
                        semester tertentu.</p>
                </div>
            </div>
        </div>
        <div class="row g-4 mt-4">
            <!-- Card 3 -->
            <div class="col-md-6">
                <div class="service-card rounded-4 p-4 text-center bg-white d-flex flex-column position-relative overflow-hidden"
                    style="transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); height: 100%; border: none; box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05); transform-origin: center; opacity: 0;"
                    onmouseover="this.style.transform='translateY(-8px) scale(1.02)'; this.style.boxShadow='0 15px 30px rgba(28, 200, 138, 0.15)';"
                    onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='0 10px 20px rgba(0, 0, 0, 0.05)';">
                    <div class="card-decoration"
                        style="position: absolute; top: 0; left: 0; width: 100%; height: 5px; background: linear-gradient(90deg, #1cc88a, #20c9a6); transition: width 0.3s ease-in-out;"
                        onmouseover="this.style.width='105%'" onmouseout="this.style.width='100%'"></div>
                    <div class="icon-container d-flex align-items-center justify-content-center mx-auto mb-4"
                        style="width: 90px; height: 90px; background-color: rgba(28, 200, 138, 0.1); border-radius: 50%; transition: all 0.3s ease;">
                        <i class="fas fa-calendar-alt fa-3x" style="color: #1cc88a; transition: transform 0.3s ease;"></i>
                    </div>
                    <h4 class="mb-3" style="font-weight: 600; color: #333; transition: color 0.3s ease;">Surat Permohonan
                        Cuti Akademik</h4>
                    <p class="text-muted">Surat ini digunakan oleh mahasiswa yang ingin mengajukan cuti akademik pada
                        semester tertentu untuk
                        alasan pribadi atau lainnya.</p>
                </div>
            </div>
            <!-- Card 4 -->
            <div class="col-md-6">
                <div class="service-card rounded-4 p-4 text-center bg-white d-flex flex-column position-relative overflow-hidden"
                    style="transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); height: 100%; border: none; box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05); transform-origin: center; opacity: 0;"
                    onmouseover="this.style.transform='translateY(-8px) scale(1.02)'; this.style.boxShadow='0 15px 30px rgba(246, 194, 62, 0.15)';"
                    onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='0 10px 20px rgba(0, 0, 0, 0.05)';">
                    <div class="card-decoration"
                        style="position: absolute; top: 0; left: 0; width: 100%; height: 5px; background: linear-gradient(90deg, #f6c23e, #fd7e14); transition: width 0.3s ease-in-out;"
                        onmouseover="this.style.width='105%'" onmouseout="this.style.width='100%'"></div>
                    <div class="icon-container d-flex align-items-center justify-content-center mx-auto mb-4"
                        style="width: 90px; height: 90px; background-color: rgba(246, 194, 62, 0.1); border-radius: 50%; transition: all 0.3s ease;">
                        <i class="fas fa-exclamation-triangle fa-3x"
                            style="color: #f6c23e; transition: transform 0.3s ease;"></i>
                    </div>
                    <h4 class="mb-3" style="font-weight: 600; color: #333; transition: color 0.3s ease;">Surat Peringatan
                        karena Pelanggaran Peraturan Akademik</h4>
                    <p class="text-muted">Surat ini digunakan sebagai peringatan bagi mahasiswa yang melanggar peraturan
                        akademik yang berlaku
                        di Politeknik Negeri Cilacap.</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Services Section End -->
@endsection
