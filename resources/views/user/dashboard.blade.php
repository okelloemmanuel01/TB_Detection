@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<h4 class="fw-bold mb-4"><i class="bi bi-house me-2 text-primary"></i>My Dashboard</h4>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card stat-card p-3" style="border-color:#0d6efd;">
            <div class="text-muted small">Total Scans</div>
            <div class="fs-2 fw-bold text-primary">{{ $totalScans }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card p-3" style="border-color:#dc3545;">
            <div class="text-muted small">TB Detected</div>
            <div class="fs-2 fw-bold text-danger">{{ $tbCount }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card p-3" style="border-color:#198754;">
            <div class="text-muted small">Normal Results</div>
            <div class="fs-2 fw-bold text-success">{{ $totalScans - $tbCount }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card p-3 d-flex flex-column justify-content-center align-items-center">
            @if($hasActiveModel)
                <a href="{{ route('user.scan') }}" class="btn btn-primary w-100">
                    <i class="bi bi-upload me-1"></i>New Scan
                </a>
            @else
                <span class="text-muted small text-center">No active model available</span>
            @endif
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white fw-semibold">Recent Scans</div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr><th>Date</th><th>X-Ray</th><th>Result</th><th>Confidence</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($recentScans as $scan)
                <tr>
                    <td class="small text-muted">{{ $scan->created_at->format('M d, Y') }}</td>
                    <td><img src="{{ Storage::url($scan->xray_image) }}" class="rounded" style="width:50px;height:50px;object-fit:cover;"></td>
                    <td>
                        <span class="badge {{ $scan->result === 'TB Detected' ? 'bg-danger' : 'bg-success' }}">
                            {{ $scan->result }}
                        </span>
                    </td>
                    <td class="small">{{ number_format($scan->confidence, 1) }}%</td>
                    <td><a href="{{ route('user.result', $scan) }}" class="btn btn-sm btn-outline-primary">View</a></td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-4">No scans yet. <a href="{{ route('user.scan') }}">Upload your first X-ray.</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
