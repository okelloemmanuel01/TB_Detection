@extends('layouts.app')
@section('title', 'ML Models')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-cpu me-2 text-primary"></i>ML Models</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
        <i class="bi bi-upload me-1"></i>Upload Model
    </button>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Version</th>
                    <th>Description</th>
                    <th>Uploaded By</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($models as $model)
                <tr>
                    <td class="fw-semibold">{{ $model->name }}</td>
                    <td><span class="badge bg-secondary">{{ $model->version ?? '—' }}</span></td>
                    <td class="text-muted small">{{ Str::limit($model->description, 50) ?? '—' }}</td>
                    <td>{{ $model->uploader->name }}</td>
                    <td class="small text-muted">{{ $model->created_at->format('M d, Y') }}</td>
                    <td>
                        @if($model->is_active)
                            <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Active</span>
                        @else
                            <span class="badge bg-light text-dark">Inactive</span>
                        @endif
                    </td>
                    <td>
                        @if(!$model->is_active)
                            <form method="POST" action="{{ route('admin.models.activate', $model) }}" class="d-inline">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm btn-outline-success me-1" title="Activate">
                                    <i class="bi bi-play-fill"></i>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.models.delete', $model) }}" class="d-inline"
                                  onsubmit="return confirm('Delete this model?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        @else
                            <span class="text-muted small">In use</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No models uploaded yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.models.upload') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Upload ML Model</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if($errors->any())
                        <div class="alert alert-danger py-2">
                            <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                        </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label">Model Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Version</label>
                        <input type="text" name="version" class="form-control" value="{{ old('version') }}" placeholder="e.g. 1.0.0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="2">{{ old('description') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Model File <span class="text-danger">*</span></label>
                        <input type="file" name="model_file" class="form-control" accept=".h5,.pkl,.pt,.pth,.keras" required>
                        <div class="form-text">Accepted: .h5, .pkl, .pt, .pth, .keras (max 500MB)</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-upload me-1"></i>Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($errors->any())
@push('scripts')
<script>new bootstrap.Modal(document.getElementById('uploadModal')).show();</script>
@endpush
@endif
@endsection
