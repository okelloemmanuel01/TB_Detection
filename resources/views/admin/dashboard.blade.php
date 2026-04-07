@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Dashboard</h4>
        <small class="text-muted">Welcome back, {{ auth()->user()->name }}</small>
    </div>
    <span class="text-muted small"><i class="bi bi-calendar3 me-1"></i>{{ now()->format('M d, Y') }}</span>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card h-100 border-0 shadow-sm" style="border-radius:16px;overflow:hidden;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted small mb-1">Total Users</p>
                        <h2 class="fw-bold mb-0">{{ $totalUsers }}</h2>
                    </div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                         style="width:44px;height:44px;background:rgba(13,110,253,0.1);">
                        <i class="bi bi-people-fill text-primary fs-5"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top">
                    <a href="{{ route('admin.users') }}" class="text-primary small text-decoration-none">
                        View all <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="card h-100 border-0 shadow-sm" style="border-radius:16px;overflow:hidden;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted small mb-1">Total Scans</p>
                        <h2 class="fw-bold mb-0">{{ $totalScans }}</h2>
                    </div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                         style="width:44px;height:44px;background:rgba(25,135,84,0.1);">
                        <i class="bi bi-file-medical-fill text-success fs-5"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top">
                    <a href="{{ route('admin.scans') }}" class="text-success small text-decoration-none">
                        View all <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="card h-100 border-0 shadow-sm" style="border-radius:16px;overflow:hidden;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted small mb-1">TB Detected</p>
                        <h2 class="fw-bold mb-0 text-danger">{{ $tbDetected }}</h2>
                    </div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                         style="width:44px;height:44px;background:rgba(220,53,69,0.1);">
                        <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top">
                    <span class="small text-muted">
                        {{ $totalScans > 0 ? round($tbDetected / $totalScans * 100) : 0 }}% positive rate
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="card h-100 border-0 shadow-sm" style="border-radius:16px;overflow:hidden;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted small mb-1">Normal Results</p>
                        <h2 class="fw-bold mb-0 text-success">{{ $totalScans - $tbDetected }}</h2>
                    </div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                         style="width:44px;height:44px;background:rgba(25,135,84,0.1);">
                        <i class="bi bi-check-circle-fill text-success fs-5"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top">
                    <span class="small text-muted">
                        {{ $totalScans > 0 ? round(($totalScans - $tbDetected) / $totalScans * 100) : 0 }}% clear rate
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Active Model --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius:16px;">
            <div class="card-body p-4">
                <h6 class="fw-semibold mb-3"><i class="bi bi-cpu-fill me-2 text-primary"></i>Active Model</h6>
                @if($activeModel)
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center bg-primary"
                             style="width:52px;height:52px;min-width:52px;">
                            <i class="bi bi-braces text-white fs-4"></i>
                        </div>
                        <div>
                            <div class="fw-bold">{{ $activeModel->name }}</div>
                            <span class="badge bg-success">Active</span>
                            @if($activeModel->version)
                                <span class="badge bg-secondary ms-1">v{{ $activeModel->version }}</span>
                            @endif
                        </div>
                    </div>
                    @if($activeModel->description)
                        <p class="text-muted small mb-3">{{ $activeModel->description }}</p>
                    @endif
                    <div class="small text-muted">
                        <i class="bi bi-clock me-1"></i>Uploaded {{ $activeModel->created_at->diffForHumans() }}
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="bi bi-cpu text-muted" style="font-size:2.5rem;opacity:0.3;"></i>
                        <p class="text-muted small mt-2 mb-3">No active model</p>
                        <a href="{{ route('admin.models') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-upload me-1"></i>Upload Model
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Detection Rate --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius:16px;">
            <div class="card-body p-4">
                <h6 class="fw-semibold mb-3"><i class="bi bi-pie-chart-fill me-2 text-primary"></i>Detection Overview</h6>
                @if($totalScans > 0)
                    @php $tbRate = round($tbDetected / $totalScans * 100); @endphp
                    <div class="text-center mb-3">
                        <div style="position:relative;display:inline-block;width:120px;height:120px;">
                            <svg viewBox="0 0 36 36" style="width:120px;height:120px;transform:rotate(-90deg);">
                                <circle cx="18" cy="18" r="15.9" fill="none" stroke="#e9ecef" stroke-width="3"/>
                                <circle cx="18" cy="18" r="15.9" fill="none" stroke="#dc3545" stroke-width="3"
                                        stroke-dasharray="{{ $tbRate }} {{ 100 - $tbRate }}"
                                        stroke-linecap="round"/>
                            </svg>
                            <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);text-align:center;">
                                <div class="fw-bold fs-4 text-danger">{{ $tbRate }}%</div>
                                <div class="small text-muted" style="font-size:10px;">TB Rate</div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-around">
                        <div class="text-center">
                            <div class="fw-bold text-danger">{{ $tbDetected }}</div>
                            <div class="small text-muted">TB Cases</div>
                        </div>
                        <div class="text-center">
                            <div class="fw-bold text-success">{{ $totalScans - $tbDetected }}</div>
                            <div class="small text-muted">Normal</div>
                        </div>
                        <div class="text-center">
                            <div class="fw-bold text-primary">{{ $totalScans }}</div>
                            <div class="small text-muted">Total</div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-bar-chart text-muted" style="font-size:2.5rem;opacity:0.3;"></i>
                        <p class="text-muted small mt-2">No scan data yet</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius:16px;">
            <div class="card-body p-4">
                <h6 class="fw-semibold mb-3"><i class="bi bi-lightning-fill me-2 text-primary"></i>Quick Actions</h6>
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.models') }}" class="btn btn-outline-primary text-start">
                        <i class="bi bi-cpu me-2"></i>Manage ML Models
                    </a>
                    <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary text-start">
                        <i class="bi bi-people me-2"></i>View Users
                        <span class="badge bg-secondary float-end">{{ $totalUsers }}</span>
                    </a>
                    <a href="{{ route('admin.scans') }}" class="btn btn-outline-success text-start">
                        <i class="bi bi-file-medical me-2"></i>All Scan Records
                        <span class="badge bg-success float-end">{{ $totalScans }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
