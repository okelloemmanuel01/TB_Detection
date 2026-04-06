<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TB Detection System')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #f0f4f8; min-height: 100vh; }
        .navbar-brand { font-weight: 700; letter-spacing: 1px; }
        .sidebar { min-height: calc(100vh - 56px); background: #1a2942; }
        .sidebar .nav-link { color: #adb5bd; border-radius: 8px; margin: 2px 8px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: rgba(255,255,255,0.1); color: #fff; }
        .sidebar .nav-link i { width: 20px; }
        .card { border: none; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); }
        .stat-card { border-left: 4px solid; }
        .badge-tb { background: #dc3545; }
        .badge-normal { background: #198754; }
        @media (max-width: 767px) { .sidebar { min-height: auto; } }
    </style>
    @stack('styles')
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <i class="bi bi-lungs-fill me-2"></i>TB Detection
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item">
                    <span class="nav-link text-white-50">
                        <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
                        <span class="badge bg-{{ auth()->user()->isAdmin() ? 'warning text-dark' : 'light text-dark' }} ms-1">
                            {{ auth()->user()->isAdmin() ? 'Admin' : 'User' }}
                        </span>
                    </span>
                </li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-outline-light btn-sm ms-2">
                            <i class="bi bi-box-arrow-right me-1"></i>Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar py-3 d-none d-md-block">
            @if(auth()->user()->isAdmin())
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->is('admin/models*') ? 'active' : '' }}" href="{{ route('admin.models') }}"><i class="bi bi-cpu me-2"></i>ML Models</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}" href="{{ route('admin.users') }}"><i class="bi bi-people me-2"></i>Users</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->is('admin/scans') ? 'active' : '' }}" href="{{ route('admin.scans') }}"><i class="bi bi-file-medical me-2"></i>All Scans</a></li>
                </ul>
            @else
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ route('user.dashboard') }}"><i class="bi bi-house me-2"></i>Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->is('scan') ? 'active' : '' }}" href="{{ route('user.scan') }}"><i class="bi bi-upload me-2"></i>New Scan</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->is('history') ? 'active' : '' }}" href="{{ route('user.history') }}"><i class="bi bi-clock-history me-2"></i>History</a></li>
                </ul>
            @endif
        </div>

        <div class="col-md-10 py-4 px-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
