@extends('layouts.app')
@section('title', 'Users')
@section('content')
<h4 class="fw-bold mb-4"><i class="bi bi-people me-2 text-primary"></i>Registered Users</h4>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Total Scans</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td class="text-muted small">{{ $user->id }}</td>
                    <td class="fw-semibold">{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td><span class="badge bg-primary rounded-pill">{{ $user->scan_results_count }}</span></td>
                    <td class="small text-muted">{{ $user->created_at->format('M d, Y') }}</td>
                    <td>
                        <a href="{{ route('admin.user.scans', $user) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye me-1"></i>View Scans
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">No users registered yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
