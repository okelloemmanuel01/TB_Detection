@extends('layouts.app')
@section('title', 'Scan Result')
@section('content')
<div class="d-flex align-items-center mb-4 gap-2">
    <a href="{{ route('user.history') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0"><i class="bi bi-file-medical me-2 text-primary"></i>Scan Result</h4>
</div>

<div class="row g-3 justify-content-center">
    <div class="col-md-10 col-lg-8">
        <!-- Result Banner -->
        @if($scan->result === 'TB Detected')
        <div class="card p-4 mb-3 text-center border-danger" style="border-width:2px!important;">
            <div class="fs-1">⚠️</div>
            <h3 class="text-danger fw-bold">TUBERCULOSIS DETECTED</h3>
            <p class="fs-5 mb-1">Confidence: <strong>{{ number_format($scan->confidence, 1) }}%</strong></p>
            <p class="text-muted small">Please consult a medical professional immediately for further evaluation.</p>
        </div>
        @else
        <div class="card p-4 mb-3 text-center border-success" style="border-width:2px!important;">
            <div class="fs-1">✅</div>
            <h3 class="text-success fw-bold">NORMAL</h3>
            <p class="fs-5 mb-1">Confidence: <strong>{{ number_format($scan->confidence, 1) }}%</strong></p>
            <p class="text-muted small">No significant TB indicators detected. Continue regular health check-ups.</p>
        </div>
        @endif

        <!-- Images -->
        <div class="row g-3">
            <div class="col-6">
                <div class="card p-2 text-center">
                    <div class="small fw-semibold text-muted mb-2">Original X-Ray</div>
                    <img src="{{ Storage::url($scan->xray_image) }}" class="img-fluid rounded" style="max-height:280px;object-fit:contain;">
                </div>
            </div>
            <div class="col-6">
                <div class="card p-2 text-center">
                    <div class="small fw-semibold text-muted mb-2">Grad-CAM Heatmap</div>
                    @if($scan->heatmap_image)
                        <img src="{{ Storage::url($scan->heatmap_image) }}" class="img-fluid rounded" style="max-height:280px;object-fit:contain;">
                    @else
                        <div class="d-flex align-items-center justify-content-center text-muted" style="height:200px;">
                            <span>Not available</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Heatmap Legend -->
        @if($scan->heatmap_image)
        <div class="card p-3 mt-3">
            <h6 class="fw-semibold mb-2"><i class="bi bi-palette me-2"></i>Heatmap Guide</h6>
            <div class="d-flex gap-3 flex-wrap small">
                <span><span class="badge" style="background:#ff0000;">■</span> Red – High influence</span>
                <span><span class="badge" style="background:#ff8800;">■</span> Orange – Medium influence</span>
                <span><span class="badge" style="background:#0000ff;">■</span> Blue – Low influence</span>
            </div>
            <p class="text-muted small mt-2 mb-0">Highlighted regions show where the model focused when making its prediction.</p>
        </div>
        @endif

        <!-- Meta -->
        <div class="card p-3 mt-3">
            <div class="row g-2 small text-muted">
                <div class="col-6"><strong>Model:</strong> {{ $scan->tbModel->name }}</div>
                <div class="col-6"><strong>Date:</strong> {{ $scan->created_at->format('M d, Y H:i') }}</div>
            </div>
        </div>

        <div class="d-flex gap-2 mt-3">
            <a href="{{ route('user.scan') }}" class="btn btn-primary"><i class="bi bi-upload me-1"></i>New Scan</a>
            <a href="{{ route('user.history') }}" class="btn btn-outline-secondary"><i class="bi bi-clock-history me-1"></i>History</a>
        </div>
    </div>
</div>
@endsection
