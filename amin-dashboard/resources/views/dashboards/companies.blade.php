@extends('layouts.dashboard')

@section('title', 'Company Dashboard')

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
                    <i class="material-icons opacity-10">work</i>
                </div>
                <div class="text-end pt-1">
                    <p class="text-sm mb-0 text-capitalize">Active Jobs</p>
                    <h4 class="mb-0">{{ $activeJobs ?? 0 }}</h4>
                </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-3">
                <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+7 </span>new this week</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-header p-3 pt-2">
                <div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                    <i class="material-icons opacity-10">person_add</i>
                </div>
                <div class="text-end pt-1">
                    <p class="text-sm mb-0 text-capitalize">Hired Freelancers</p>
                    <h4 class="mb-0">{{ $hiredFreelancers ?? 0 }}</h4>
                </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-3">
                <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+15% </span>than last month</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-header p-3 pt-2">
                <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                    <i class="material-icons opacity-10">attach_money</i>
                </div>
                <div class="text-end pt-1">
                    <p class="text-sm mb-0 text-capitalize">Budget Spent</p>
                    <h4 class="mb-0">${{ $budgetSpent ?? 0 }}</h4>
                </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-3">
                <p class="mb-0"><span class="text-danger text-sm font-weight-bolder">-5% </span>under budget</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card">
            <div class="card-header p-3 pt-2">
                <div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                    <i class="material-icons opacity-10">star</i>
                </div>
                <div class="text-end pt-1">
                    <p class="text-sm mb-0 text-capitalize">Average Rating</p>
                    <h4 class="mb-0">{{ $averageRating ?? '4.8' }}</h4>
                </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-3">
                <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+0.2 </span>from last project</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-7 mb-lg-0 mb-4">
        <div class="card z-index-2 h-100">
            <div class="card-header pb-0 pt-3 bg-transparent">
                <h6 class="text-capitalize">Project Timeline</h6>
                <p class="text-sm mb-0">
                    <i class="fa fa-arrow-up text-success"></i>
                    <span class="font-weight-bold">Progress overview</span>
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
        <div class="card">
            <div class="card-header pb-0 p-3">
                <h6 class="mb-1">Recent Projects</h6>
                <p class="text-sm">Latest job postings and status</p>
            </div>
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div>
                                            <img src="https://demos.creative-tim.com/soft-ui-dashboard/assets/img/small-logos/logo-xd.svg" class="avatar avatar-sm me-3">
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">Mobile App Development</h6>
                                            <p class="text-xs text-secondary mb-0">Posted 3 days ago</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    <span class="badge badge-sm bg-gradient-success">Active</span>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">12 applicants</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div>
                                            <img src="https://demos.creative-tim.com/soft-ui-dashboard/assets/img/small-logos/logo-atlassian.svg" class="avatar avatar-sm me-3">
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">Web Design Project</h6>
                                            <p class="text-xs text-secondary mb-0">Posted 1 week ago</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    <span class="badge badge-sm bg-gradient-warning">In Progress</span>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">8 applicants</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div>
                                            <img src="https://demos.creative-tim.com/soft-ui-dashboard/assets/img/small-logos/logo-slack.svg" class="avatar avatar-sm me-3">
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">SEO Optimization</h6>
                                            <p class="text-xs text-secondary mb-0">Posted 2 weeks ago</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    <span class="badge badge-sm bg-gradient-success">Completed</span>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">15 applicants</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header pb-0 p-3">
                <h6 class="mb-1">Freelancer Applications</h6>
                <p class="text-sm">Review and manage applications for your jobs</p>
            </div>
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Freelancer</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Job Applied</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Rating</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Experience</th>
                                <th class="text-secondary opacity-7"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div>
                                            <div class="avatar avatar-sm me-3 bg-gradient-dark">
                                                JD
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">John Doe</h6>
                                            <p class="text-xs text-secondary mb-0">john@example.com</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">Mobile App Development</p>
                                    <p class="text-xs text-secondary mb-0">Applied 2 days ago</p>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    <span class="text-xs font-weight-bold">4.9</span>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">5 years</span>
                                </td>
                                <td class="align-middle">
                                    <button class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto">
                                        <i class="ni ni-bold-right" aria-hidden="true"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div>
                                            <div class="avatar avatar-sm me-3 bg-gradient-primary">
                                                JS
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">Jane Smith</h6>
                                            <p class="text-xs text-secondary mb-0">jane@example.com</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">Web Design Project</p>
                                    <p class="text-xs text-secondary mb-0">Applied 1 day ago</p>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    <span class="text-xs font-weight-bold">4.7</span>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">3 years</span>
                                </td>
                                <td class="align-middle">
                                    <button class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto">
                                        <i class="ni ni-bold-right" aria-hidden="true"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
@include('layouts.footers.auth.footer')
@endsection
