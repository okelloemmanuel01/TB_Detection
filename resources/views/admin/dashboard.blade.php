@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('content')
<h4 class="fw-bold mb-4"><i class="bi bi-speedometer2 me-2 text-primary"></i>Admin Dashboard</h4>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card stat-card p-3" style="border-color:#0d6efd;">
            <div class="text-muted small">Total Users</div>
            <div class="fs-2 fw-bold text-primary">{{ $totalUsers }}</div>
            <i class="bi bi-people text-primary opacity-25" style="font-size:2rem;position:absolute;right:16px;top:16px;"></i>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card p-3" style="border-color:#198754;">
            <div class="text-muted small">Total Scans</div>
            <div class="fs-2 fw-bold text-success">{{ $totalScans }}</div>
            <i class="bi bi-file-medical text-success opacity-25" style="font-size:2rem;position:absolute;right:16px;top:16px;"></i>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card p-3" style="border-color:#dc3545;">
            <div class="text-muted small">TB Detected</div>
            <div class="fs-2 fw-bold text-danger">{{ $tbDetected }}</div>
            <i class="bi bi-exclamation-triangle text-danger opacity-25" style="font-size:2rem;position:absolute;right:16px;top:16px;"></i>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card p-3" style="border-color:#ffc107;">
            <div class="text-muted small">Active Model</div>
            <div class="fw-bold text-truncate">{{ $activeModel?->name ?? 'None' }}</div>
            <div class="small text-muted">{{ $activeModel?->version ?? '—' }}</div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card p-3 h-100">
            <h6 class="fw-semibold mb-3">Quick Actions</h6>
            <a href="{{ route('admin.models') }}" class="btn btn-outline-primary w-100 mb-2"><i class="bi bi-cpu me-2"></i>Manage ML Models</a>
            <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary w-100 mb-2"><i class="bi bi-people me-2"></i>View Users</a>
            <a href="{{ route('admin.scans') }}" class="btn btn-outline-success w-100"><i class="bi bi-file-medical me-2"></i>All Scan Records</a>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card p-3">
            <h6 class="fw-semibold mb-3">System Status</h6>
            @if($activeModel)
                <div class="alert alert-success py-2 mb-2">
                    <i class="bi bi-check-circle me-2"></i>Active model: <strong>{{ $activeModel->name }}</strong> (v{{ $activeModel->version }})
                </div>
            @else
                <div class="alert alert-warning py-2 mb-2">
                    <i class="bi bi-exclamation-triangle me-2"></i>No active model. <a href="{{ route('admin.models') }}">Upload one now.</a>
                </div>
            @endif
            <div class="d-flex gap-3 mt-2">
                <div class="text-center">
                    <div class="fs-4 fw-bold text-danger">{{ $totalScans > 0 ? round($tbDetected/$totalScans*100) : 0 }}%</div>
                    <div class="small text-muted">TB Rate</div>
                </div>
                <div class="text-center">
                    <div class="fs-4 fw-bold text-success">{{ $totalScans - $tbDetected }}</div>
                    <div class="small text-muted">Normal Results</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
