<x-app-layout>
    <div class="container">
        <h2>Audit Log Detail</h2>
        <a href="{{ url()->previous() }}" class="btn btn-secondary mb-3">Back</a>
        <div class="card">
            <div class="card-body">
                <strong>Date:</strong> {{ $log->created_at }}<br>
                <strong>User:</strong> {{ $log->user->name ?? '-' }}<br>
                <strong>Model:</strong> {{ $log->auditable_type }}<br>
                <strong>Model ID:</strong> {{ $log->auditable_id }}<br>
                <strong>Action:</strong> {{ $log->action }}<br>
                <strong>Remarks:</strong> {{ $log->remarks ?? '-' }}<br>
                <strong>Changes:</strong>
                @if($log->changes)
                    <pre>{{ json_encode($log->changes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                @else
                    <span>-</span>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
