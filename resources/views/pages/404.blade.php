@extends('layouts.app')

@section('content')
<nav class="navbar navbar-expand-lg position-absolute top-0 z-index-3 w-100 shadow-none my-3 navbar-transparent mt-4">
    <div class="container">
        <a class="navbar-brand text-light mb-0 me-1 ms-lg-0 ms-3 " href="{{ route('home') }}">
            <strong>Smart Lab</strong>
            <small> by IoT Lab UNS</small>
        </a>
        <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse"
            data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon mt-2">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
            </span>
        </button>
        <div class="collapse navbar-collapse" id="navigation">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center me-2 active" aria-current="page"
                        href="{{ route('home') }}">
                        <i class="fa fa-chart-pie opacity-6  me-1"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link me-2" href="{{ route('sign-in-static') }}">
                        <i class="fas fa-key opacity-6  me-1"></i>
                        Sign In
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav d-lg-block d-none">
                <li class="nav-item">
                    <a href="https://www.creative-tim.com/product/argon-dashboard-laravel" targte="_blank"
                        class="btn btn-sm mb-0 me-1 bg-gradient-primary">Learn More</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- End Navbar -->
<main class="main-content  mt-0">
    <div class="page-header align-items-start min-vh-50 pt-5 pb-11 m-3 border-radius-lg"
        style="background-image: url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/signup-cover.jpg'); background-position: top;">
        <span class="mask bg-gradient-dark opacity-6"></span>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 text-center mx-auto">
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row mt-lg-n10 mt-md-n11 mt-n10 justify-content-center">
            <div class="col-xl-6 col-lg-5 col-md-7 mx-auto">
                <div class="card z-index-0 shadow my-4">
                    <div class="card-header text-center pt-4">
                        <h1>Not Found</h1>
                    </div>
                    <div class="card-body my-4">
                        <div class="row d-flex justify-content-center">
                            <h4>The page you are looking for does not exist.</h4>
                        </div>
                        <div class="row d-flex justify-content-center">
                            <a href="{{ route('home') }}" class="btn btn-primary my-2">Back to Home</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection