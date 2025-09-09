@extends('layouts.dashboard')

@section('title', 'Service Leaders Dashboard')

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
                    <i class="material-icons opacity-10">business</i>
                </div>
                <div class="text-end pt-1">
                    <p class="text-sm mb-0 text-capitalize">Services Offered</p>
                    <h4 class="mb-0">{{ $servicesOffered ?? 0 }}</h4>
                </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-3">
                <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+10% </span>than last month</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-header p-3 pt-2">
                <div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                    <i class="material-icons opacity-10">star</i>
                </div>
                <div class="text-end pt-1">
                    <p class="text-sm mb-0 text-capitalize">Client Satisfaction</p>
                    <h4 class="mb-0">{{ $clientSatisfaction ?? '95%' }}</h4>
                </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-3">
                <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+2% </span>than last quarter</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-header p-3 pt-2">
                <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                    <i class="material-icons opacity-10">timeline</i>
                </div>
                <div class="text-end pt-1">
                    <p class="text-sm mb-0 text-capitalize">Projects Completed</p>
                    <h4 class="mb-0">{{ $projectsCompleted ?? 0 }}</h4>
                </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-3">
                <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+18% </span>than last month</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card">
            <div class="card-header p-3 pt-2">
                <div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                    <i class="material-icons opacity-10">attach_money</i>
                </div>
                <div class="text-end pt-1">
                    <p class="text-sm mb-0 text-capitalize">Revenue Generated</p>
                    <h4 class="mb-0">${{ $revenueGenerated ?? 0 }}</h4>
                </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-3">
                <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+12% </span>than last month</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-6 mb-lg-0 mb-4">
        <div class="card">
            <div class="card-header pb-0 p-3">
                <h6 class="mb-1">Service Performance</h6>
                <p class="text-sm">Overview of service delivery metrics</p>
            </div>
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-7">
                        <div class="chart">
                            <canvas id="chart-pie" class="chart-canvas" height="200"></canvas>
                        </div>
                    </div>
                    <div class="col-5 my-auto">
                        <span class="badge badge-sm bg-gradient-success">Completed</span>
                        <h4 class="mb-0 font-weight-bolder">85%</h4>
                        <span class="badge badge-sm bg-gradient-warning">In Progress</span>
                        <h4 class="mb-0 font-weight-bolder">12%</h4>
                        <span class="badge badge-sm bg-gradient-danger">Delayed</span>
                        <h4 class="mb-0 font-weight-bolder">3%</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header pb-0 p-3">
                <h6 class="mb-1">Recent Client Feedback</h6>
                <p class="text-sm">Latest reviews and ratings</p>
            </div>
            <div class="card-body p-3">
                <div class="timeline timeline-one-side">
                    <div class="timeline-block mb-3">
                        <span class="timeline-step">
                            <i class="ni ni-bell-55 text-success text-gradient"></i>
                        </span>
                        <div class="timeline-content">
                            <h6 class="text-dark text-sm font-weight-bold mb-0">Excellent service!</h6>
                            <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">Client ABC - 5 stars</p>
                            <p class="text-sm mt-3 mb-2">"The team delivered beyond expectations. Highly recommended."</p>
                        </div>
                    </div>
                    <div class="timeline-block mb-3">
                        <span class="timeline-step">
                            <i class="ni ni-bell-55 text-info text-gradient"></i>
                        </span>
                        <div class="timeline-content">
                            <h6 class="text-dark text-sm font-weight-bold mb-0">Great communication</h6>
                            <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">Client XYZ - 4.5 stars</p>
                            <p class="text-sm mt-3 mb-2">"Responsive and professional throughout the project."</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
@include('layouts.footers.auth.footer')
@endsection
