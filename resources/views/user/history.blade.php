@extends('layouts.app')
@section('title', 'Scan History')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Scan History</h4>
    <a href="{{ route('user.scan') }}" class="btn btn-primary btn-sm"><i class="bi bi-upload me-1"></i>New Scan</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr><th>Date</th><th>X-Ray</th><th>Result</th><th>Confidence</th><th>Model</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($scans as $scan)
                <tr>
                    <td class="small text-muted">{{ $scan->created_at->format('M d, Y H:i') }}</td>
                    <td><img src="{{ Storage::url($scan->xray_image) }}" class="rounded" style="width:55px;height:55px;object-fit:cover;"></td>
                    <td>
                        <span class="badge {{ $scan->result === 'TB Detected' ? 'bg-danger' : 'bg-success' }}">
                            {{ $scan->result === 'TB Detected' ? '⚠️ TB Detected' : '✅ Normal' }}
                        </span>
                    </td>
                    <td>
                        <div class="progress mb-1" style="width:80px;height:6px;">
                            <div class="progress-bar {{ $scan->result === 'TB Detected' ? 'bg-danger' : 'bg-success' }}"
                                 style="width:{{ $scan->confidence }}%"></div>
                        </div>
                        <small class="text-muted">{{ number_format($scan->confidence, 1) }}%</small>
                    </td>
                    <td class="small text-muted">{{ $scan->tbModel->name }}</td>
                    <td><a href="{{ route('user.result', $scan) }}" class="btn btn-sm btn-outline-primary">View</a></td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">No scans yet. <a href="{{ route('user.scan') }}">Upload your first X-ray.</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($scans->hasPages())
    <div class="card-footer bg-white">{{ $scans->links() }}</div>
    @endif
</div>
@endsection
