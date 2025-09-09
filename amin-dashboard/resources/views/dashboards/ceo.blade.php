@extends('layouts.dashboard')

@section('title', 'CEO Dashboard')

@section('sidebar')
@include('layouts.navbars.auth.sidebar')
@endsection

@section('navbar')
@include('layouts.navbars.auth.nav')
@endsection

@section('content')
<div class="row">
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-header p-3 pt-2">
                <div class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">
                    <i class="material-icons opacity-10">weekend</i>
                </div>
                <div class="text-end pt-1">
                    <p class="text-sm mb-0 text-capitalize">Total Companies</p>
                    <h4 class="mb-0">{{ $companiesCount ?? 0 }}</h4>
                </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-3">
                <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+55% </span>than last month</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-header p-3 pt-2">
                <div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                    <i class="material-icons opacity-10">person</i>
                </div>
                <div class="text-end pt-1">
                    <p class="text-sm mb-0 text-capitalize">Total Freelancers</p>
                    <h4 class="mb-0">{{ $freelancersCount ?? 0 }}</h4>
                </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-3">
                <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+3% </span>than last month</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-header p-3 pt-2">
                <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                    <i class="material-icons opacity-10">store</i>
                </div>
                <div class="text-end pt-1">
                    <p class="text-sm mb-0 text-capitalize">Active Jobs</p>
                    <h4 class="mb-0">{{ $activeJobsCount ?? 0 }}</h4>
                </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-3">
                <p class="mb-0"><span class="text-danger text-sm font-weight-bolder">-2% </span>than yesterday</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card">
            <div class="card-header p-3 pt-2">
                <div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                    <i class="material-icons opacity-10">credit_card</i>
                </div>
                <div class="text-end pt-1">
                    <p class="text-sm mb-0 text-capitalize">Revenue</p>
                    <h4 class="mb-0">${{ $revenue ?? 0 }}</h4>
                </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-3">
                <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+5% </span>than yesterday</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-7 mb-lg-0 mb-4">
        <div class="card z-index-2 h-100">
            <div class="card-header pb-0 pt-3 bg-transparent">
                <h6 class="text-capitalize">Sales overview</h6>
                <p class="text-sm mb-0">
                    <i class="fa fa-arrow-up text-success"></i>
                    <span class="font-weight-bold">4% more</span> in 2021
                </p>
            </div>
            <div class="card-body p-3">
                <div class="chart">
                    <canvas id="chart-line" class="chart-canvas" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card card-carousel overflow-hidden h-100 p-0">
            <div id="carouselExampleCaptions" class="carousel slide h-100" data-bs-ride="carousel">
                <div class="carousel-inner border-radius-lg h-100">
                    <div class="carousel-item h-100 active" style="background-image: url('https://demos.creative-tim.com/soft-ui-dashboard/assets/img/carousel-1.jpg'); background-size: cover;">
                        <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
                            <div class="icon icon-shape icon-sm bg-white shadow text-center border-radius-md mb-3">
                                <i class="ni ni-camera-compact text-dark opacity-10"></i>
                            </div>
                            <h5 class="text-white mb-1">Get started with Freelancer Platform</h5>
                            <p>Manage your talent acquisition efficiently.</p>
                        </div>
                    </div>
                    <div class="carousel-item h-100" style="background-image: url('https://demos.creative-tim.com/soft-ui-dashboard/assets/img/carousel-2.jpg'); background-size: cover;">
                        <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
                            <div class="icon icon-shape icon-sm bg-white shadow text-center border-radius-md mb-3">
                                <i class="ni ni-bulb-61 text-dark opacity-10"></i>
                            </div>
                            <h5 class="text-white mb-1">Faster decision making</h5>
                            <p>Access real-time analytics and reports.</p>
                        </div>
                    </div>
                    <div class="carousel-item h-100" style="background-image: url('https://demos.creative-tim.com/soft-ui-dashboard/assets/img/carousel-3.jpg'); background-size: cover;">
                        <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
                            <div class="icon icon-shape icon-sm bg-white shadow text-center border-radius-md mb-3">
                                <i class="ni ni-trophy text-dark opacity-10"></i>
                            </div>
                            <h5 class="text-white mb-1">Award-winning platform</h5>
                            <p>Trusted by leading companies worldwide.</p>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev w-5 me-3" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next w-5 me-3" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
@include('layouts.footers.auth.footer')
@endsection
