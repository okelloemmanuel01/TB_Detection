@extends('layouts.app')
@section('title', 'New Scan')
@section('content')
<h4 class="fw-bold mb-4"><i class="bi bi-upload me-2 text-primary"></i>Upload Chest X-Ray</h4>

@if(!$activeModel)
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle me-2"></i>No active ML model available. Please contact the administrator.
    </div>
@else
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card p-4">
            <div class="alert alert-info py-2 mb-3">
                <i class="bi bi-cpu me-2"></i>Using model: <strong>{{ $activeModel->name }}</strong>
                @if($activeModel->version) <span class="badge bg-secondary ms-1">v{{ $activeModel->version }}</span> @endif
            </div>

            @if($errors->any())
                <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('user.scan.submit') }}" enctype="multipart/form-data" id="scanForm">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Chest X-Ray Image</label>
                    <div class="border rounded-3 p-4 text-center" id="dropZone" style="cursor:pointer;border-style:dashed!important;">
                        <i class="bi bi-image text-muted" style="font-size:2.5rem;" id="dropIcon"></i>
                        <p class="text-muted mb-2" id="dropText">Click or drag & drop your X-ray image here</p>
                        <small class="text-muted">JPEG, PNG – max 5MB</small>
                        <input type="file" name="xray" id="xrayInput" class="d-none" accept="image/jpeg,image/png,image/jpg" required>
                        <img id="preview" class="img-fluid rounded mt-3 d-none" style="max-height:300px;">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100" id="submitBtn">
                    <i class="bi bi-search me-2"></i>Analyze X-Ray
                </button>
            </form>
        </div>

        <div class="card p-3 mt-3">
            <h6 class="fw-semibold mb-2"><i class="bi bi-info-circle me-2 text-primary"></i>How it works</h6>
            <ol class="mb-0 small text-muted">
                <li>Upload a clear chest X-ray image</li>
                <li>Our AI model analyzes the image for TB indicators</li>
                <li>A Grad-CAM heatmap highlights areas of concern</li>
                <li>Results are saved to your history</li>
            </ol>
            @if(!config('app.ml_api_url'))
            <div class="alert alert-warning py-2 mt-2 mb-0 small">
                <i class="bi bi-exclamation-triangle me-1"></i>
                <strong>Note:</strong> The ML analysis server must be running locally for scans to work.
            </div>
            @endif
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
const input = document.getElementById('xrayInput');
const dropZone = document.getElementById('dropZone');
const preview = document.getElementById('preview');
const dropText = document.getElementById('dropText');
const dropIcon = document.getElementById('dropIcon');
const submitBtn = document.getElementById('submitBtn');

dropZone.addEventListener('click', () => input.click());

input.addEventListener('change', () => {
    const file = input.files[0];
    if (file) {
        preview.src = URL.createObjectURL(file);
        preview.classList.remove('d-none');
        dropText.textContent = file.name;
        dropIcon.classList.add('d-none');
    }
});

dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('border-primary'); });
dropZone.addEventListener('dragleave', () => dropZone.classList.remove('border-primary'));
dropZone.addEventListener('drop', e => {
    e.preventDefault();
    dropZone.classList.remove('border-primary');
    input.files = e.dataTransfer.files;
    input.dispatchEvent(new Event('change'));
});

document.getElementById('scanForm').addEventListener('submit', () => {
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Analyzing...';
});
</script>
@endpush
