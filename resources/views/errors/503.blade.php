@extends('layouts.main')

@section('container')
    <!-- Header Start -->
    <div class="container-fluid hero-header bg-light py-5 mb-5">
        <div class="container py-5">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 mb-3 animated slideInDown">503 Error</h1>
                    <nav aria-label="breadcrumb animated slideInDown">
                    </nav>
                </div>
                <div class="col-lg-6 animated fadeIn">
                    <img class="img-fluid animated pulse infinite" style="animation-duration: 3s;" src="img/hero-2.png"
                        alt="">
                </div>
            </div>
        </div>
    </div>
    <!-- Header End -->


    <!-- 404 Start -->
    <div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container text-center">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <i class="bi bi-tools display-1 text-primary"></i>
                    <h1 class="display-1">503</h1>
                    <h1 class="mb-4">Service Unavailable</h1>
                    <p class="mb-4">Our services are currently unavailable due to maintenance. We apologize for the inconvenience. Please check back later.</p>
                </div>
            </div>
        </div>
    </div>
    <!-- 404 End -->
@endsection
