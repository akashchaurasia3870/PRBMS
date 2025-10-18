<x-app-layout>
    <div class="container">
        <h2>Audit Logs</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Date/Time</th>
                    <th>User</th>
                    <th>Model</th>
                    <th>Model ID</th>
                    <th>Action</th>
                    <th>Changes</th>
                    <th>Remarks</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
            @foreach($logs as $log)
                <tr>
                    <td>{{ $log->created_at }}</td>
                    <td>{{ $log->user->name ?? '-' }}</td>
                    <td>{{ class_basename($log->auditable_type) }}</td>
                    <td>{{ $log->auditable_id }}</td>
                    <td>{{ $log->action }}</td>
                    <td>
                        @if($log->changes)
                            <pre style="max-width:300px;overflow:auto;">{{ json_encode($log->changes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $log->remarks }}</td>
                    <td><a href="{{ route('audit_logs.show', $log->id) }}" class="btn btn-sm btn-outline-info">View</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $logs->links() }}
    </div>
</x-app-layout>
