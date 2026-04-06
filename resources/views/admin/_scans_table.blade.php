<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    @isset($showUser)<th>User</th>@endisset
                    <th>X-Ray</th>
                    <th>Result</th>
                    <th>Confidence</th>
                    <th>Model Used</th>
                    <th>Date</th>
                    <th>Heatmap</th>
                </tr>
            </thead>
            <tbody>
                @forelse($scans as $scan)
                <tr>
                    @isset($showUser)<td>{{ $scan->user->name }}</td>@endisset
                    <td>
                        <img src="{{ Storage::url($scan->xray_image) }}" alt="X-Ray"
                             class="rounded" style="width:60px;height:60px;object-fit:cover;">
                    </td>
                    <td>
                        <span class="badge {{ $scan->result === 'TB Detected' ? 'bg-danger' : 'bg-success' }}">
                            {{ $scan->result === 'TB Detected' ? '⚠️ TB Detected' : '✅ Normal' }}
                        </span>
                    </td>
                    <td>
                        <div class="progress" style="width:80px;height:8px;">
                            <div class="progress-bar {{ $scan->result === 'TB Detected' ? 'bg-danger' : 'bg-success' }}"
                                 style="width:{{ $scan->confidence }}%"></div>
                        </div>
                        <small class="text-muted">{{ number_format($scan->confidence, 1) }}%</small>
                    </td>
                    <td class="small text-muted">{{ $scan->tbModel->name }}</td>
                    <td class="small text-muted">{{ $scan->created_at->format('M d, Y H:i') }}</td>
                    <td>
                        @if($scan->heatmap_image)
                            <img src="{{ Storage::url($scan->heatmap_image) }}" alt="Heatmap"
                                 class="rounded" style="width:60px;height:60px;object-fit:cover;"
                                 data-bs-toggle="tooltip" title="Grad-CAM Heatmap">
                        @else
                            <span class="text-muted small">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No scan records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
