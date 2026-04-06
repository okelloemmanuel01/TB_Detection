@extends('layouts.app')
@section('title', 'All Scans')
@section('content')
<h4 class="fw-bold mb-4"><i class="bi bi-file-medical me-2 text-primary"></i>All Scan Records</h4>

@include('admin._scans_table', ['scans' => $scans, 'showUser' => true])

<div class="mt-3">{{ $scans->links() }}</div>
@endsection
