@extends('layouts.dashboard')

@section('title', 'Employee Dashboard')

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
                    <i class="material-icons opacity-10">assignment</i>
                </div>
                <div class="text-end pt-1">
                    <p class="text-sm mb-0 text-capitalize">My Tasks</p>
                    <h4 class="mb-0">{{ $myTasks ?? 0 }}</h4>
                </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-3">
                <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+5 </span>new today</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-header p-3 pt-2">
                <div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                    <i class="material-icons opacity-10">check_circle</i>
                </div>
                <div class="text-end pt-1">
                    <p class="text-sm mb-0 text-capitalize">Completed</p>
                    <h4 class="mb-0">{{ $completedTasks ?? 0 }}</h4>
                </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-3">
                <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+20% </span>this week</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-header p-3 pt-2">
                <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                    <i class="material-icons opacity-10">schedule</i>
                </div>
                <div class="text-end pt-1">
                    <p class="text-sm mb-0 text-capitalize">Hours Worked</p>
                    <h4 class="mb-0">{{ $hoursWorked ?? 0 }}</h4>
                </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-3">
                <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+8h </span>than last week</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card">
            <div class="card-header p-3 pt-2">
                <div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                    <i class="material-icons opacity-10">grade</i>
                </div>
                <div class="text-end pt-1">
                    <p class="text-sm mb-0 text-capitalize">Performance Score</p>
                    <h4 class="mb-0">{{ $performanceScore ?? '95%' }}</h4>
                </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-3">
                <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+3% </span>improvement</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-8 mb-lg-0 mb-4">
        <div class="card">
            <div class="card-header pb-0 p-3">
                <h6 class="mb-1">My Tasks Overview</h6>
                <p class="text-sm">Current tasks and deadlines</p>
            </div>
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Task</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Priority</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Deadline</th>
                                <th class="text-secondary opacity-7"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div>
                                            <img src="https://demos.creative-tim.com/soft-ui-dashboard/assets/img/small-logos/logo-xd.svg" class="avatar avatar-sm me-3">
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">Update website design</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-sm bg-gradient-danger">High</span>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    <span class="badge badge-sm bg-gradient-warning">In Progress</span>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">2024-10-15</span>
                                </td>
                                <td class="align-middle">
                                    <a href="javascript:;" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit task">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div>
                                            <img src="https://demos.creative-tim.com/soft-ui-dashboard/assets/img/small-logos/logo-atlassian.svg" class="avatar avatar-sm me-3">
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">Review code changes</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-sm bg-gradient-info">Medium</span>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    <span class="badge badge-sm bg-gradient-success">Completed</span>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">2024-10-10</span>
                                </td>
                                <td class="align-middle">
                                    <a href="javascript:;" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit task">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header pb-0 p-3">
                <h6 class="mb-1">Team Announcements</h6>
                <p class="text-sm">Latest updates from management</p>
            </div>
            <div class="card-body p-3">
                <div class="timeline timeline-one-side">
                    <div class="timeline-block mb-3">
                        <span class="timeline-step">
                            <i class="ni ni-bell-55 text-success text-gradient"></i>
                        </span>
                        <div class="timeline-content">
                            <h6 class="text-dark text-sm font-weight-bold mb-0">New Project Kickoff</h6>
                            <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">2 hours ago</p>
                            <p class="text-sm mt-3 mb-2">"Exciting new project starting next week. Check your email for details."</p>
                        </div>
                    </div>
                    <div class="timeline-block mb-3">
                        <span class="timeline-step">
                            <i class="ni ni-bell-55 text-info text-gradient"></i>
                        </span>
                        <div class="timeline-content">
                            <h6 class="text-dark text-sm font-weight-bold mb-0">Team Meeting</h6>
                            <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">1 day ago</p>
                            <p class="text-sm mt-3 mb-2">"Weekly standup moved to 10 AM tomorrow."</p>
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
