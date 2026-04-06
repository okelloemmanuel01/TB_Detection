@extends('layouts.app')
@section('title', "{{ $user->name }}'s Scans")
@section('content')
<div class="d-flex align-items-center mb-4 gap-2">
    <a href="{{ route('admin.users') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0"><i class="bi bi-person me-2 text-primary"></i>{{ $user->name }}'s Scan Records</h4>
</div>

<div class="card mb-3 p-3">
    <div class="row g-2">
        <div class="col-auto"><span class="text-muted">Email:</span> <strong>{{ $user->email }}</strong></div>
        <div class="col-auto"><span class="text-muted">Total Scans:</span> <strong>{{ $scans->total() }}</strong></div>
        <div class="col-auto"><span class="text-muted">Joined:</span> <strong>{{ $user->created_at->format('M d, Y') }}</strong></div>
    </div>
</div>

@include('admin._scans_table', ['scans' => $scans])

<div class="mt-3">{{ $scans->links() }}</div>
@endsection
