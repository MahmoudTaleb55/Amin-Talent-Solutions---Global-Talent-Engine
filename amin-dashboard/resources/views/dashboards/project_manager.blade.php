@extends('layouts.dashboard')

@section('title', 'Project Manager Dashboard')

@section('sidebar')
@include('layouts.navbars.auth.sidebar')
@endsection

@section('navbar')
@include('layouts.navbars.auth.nav')
@endsection

@section('content')
<div class="row">
    <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-header p-3 pt-2">
                <div class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">
                    <i class="material-icons opacity-10">task</i>
                </div>
                <div class="text-end pt-1">
                    <p class="text-sm mb-0 text-capitalize">Assigned Projects</p>
                    <h4 class="mb-0">{{ $assignedProjects ?? 0 }}</h4>
                </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-3">
                <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+12% </span>than last week</p>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-header p-3 pt-2">
                <div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                    <i class="material-icons opacity-10">group</i>
                </div>
                <div class="text-end pt-1">
                    <p class="text-sm mb-0 text-capitalize">Team Members</p>
                    <h4 class="mb-0">{{ $teamMembers ?? 0 }}</h4>
                </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-3">
                <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+8% </span>than last month</p>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-sm-6">
        <div class="card">
            <div class="card-header p-3 pt-2">
                <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                    <i class="material-icons opacity-10">check_circle</i>
                </div>
                <div class="text-end pt-1">
                    <p class="text-sm mb-0 text-capitalize">Completed Tasks</p>
                    <h4 class="mb-0">{{ $completedTasks ?? 0 }}</h4>
                </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-3">
                <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+15% </span>than yesterday</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-8 mb-lg-0 mb-4">
        <div class="card">
            <div class="card-header pb-0 p-3">
                <div class="d-flex justify-content-between">
                    <h6 class="mb-2">Project Progress</h6>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table align-items-center">
                    <tbody>
                        <tr>
                            <td class="w-30">
                                <div class="d-flex px-2 py-1 align-items-center">
                                    <div>
                                        <img src="https://demos.creative-tim.com/soft-ui-dashboard/assets/img/small-logos/logo-xd.svg" class="avatar avatar-sm me-3">
                                    </div>
                                    <div class="ms-4">
                                        <h6 class="text-sm mb-0">Project Alpha</h6>
                                        <p class="text-xs text-secondary mb-0">Web Development</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">Budget:</p>
                                    <h6 class="text-sm mb-0">$14,000</h6>
                                </div>
                            </td>
                            <td>
                                <div class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">Status:</p>
                                    <h6 class="text-sm mb-0">In Progress</h6>
                                </div>
                            </td>
                            <td class="align-middle text-sm">
                                <div class="col text-center">
                                    <p class="text-xs font-weight-bold mb-0">Completion:</p>
                                    <h6 class="text-sm mb-0">75%</h6>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="w-30">
                                <div class="d-flex px-2 py-1 align-items-center">
                                    <div>
                                        <img src="https://demos.creative-tim.com/soft-ui-dashboard/assets/img/small-logos/logo-atlassian.svg" class="avatar avatar-sm me-3">
                                    </div>
                                    <div class="ms-4">
                                        <h6 class="text-sm mb-0">Project Beta</h6>
                                        <p class="text-xs text-secondary mb-0">Mobile App</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">Budget:</p>
                                    <h6 class="text-sm mb-0">$8,500</h6>
                                </div>
                            </td>
                            <td>
                                <div class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">Status:</p>
                                    <h6 class="text-sm mb-0">Pending</h6>
                                </div>
                            </td>
                            <td class="align-middle text-sm">
                                <div class="col text-center">
                                    <p class="text-xs font-weight-bold mb-0">Completion:</p>
                                    <h6 class="text-sm mb-0">45%</h6>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header pb-0 p-3">
                <h6 class="mb-0">Team Performance</h6>
            </div>
            <div class="card-body p-3">
                <ul class="list-group">
                    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                        <div class="d-flex align-items-center">
                            <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                <i class="ni ni-mobile-button text-white opacity-10"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <h6 class="mb-1 text-dark text-sm">John Doe</h6>
                                <span class="text-xs">Developer</span>
                            </div>
                        </div>
                        <div class="d-flex">
                            <button class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto"><i class="ni ni-bold-right" aria-hidden="true"></i></button>
                        </div>
                    </li>
                    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                        <div class="d-flex align-items-center">
                            <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                <i class="ni ni-tag text-white opacity-10"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <h6 class="mb-1 text-dark text-sm">Jane Smith</h6>
                                <span class="text-xs">Designer</span>
                            </div>
                        </div>
                        <div class="d-flex">
                            <button class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto"><i class="ni ni-bold-right" aria-hidden="true"></i></button>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
@include('layouts.footers.auth.footer')
@endsection
