<x-app-layout>
    <div class="">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">📊 Leave Dashboard</h1>
                            <p class="text-gray-600 mt-1">Overview of leave requests and statistics</p>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('dashboard_leave.leave_request_view') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow-sm transition duration-200">
                                ➕ Apply Leave
                            </a>
                            <a href="{{ route('dashboard_leave.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow-sm transition duration-200">
                                📋 View All Leaves
                            </a>
                        </div>
                    </div>

                    <!-- Quick Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <!-- Total Leaves -->
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-blue-100 text-sm font-medium">Total Leaves</p>
                                    <p class="text-3xl font-bold">{{ $statistics['total'] ?? 0 }}</p>
                                </div>
                                <div class="text-4xl opacity-80">📝</div>
                            </div>
                        </div>

                        <!-- Pending Leaves -->
                        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl p-6 text-white shadow-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-yellow-100 text-sm font-medium">Pending</p>
                                    <p class="text-3xl font-bold">{{ $statistics['pending'] ?? 0 }}</p>
                                </div>
                                <div class="text-4xl opacity-80">⏳</div>
                            </div>
                        </div>

                        <!-- Approved Leaves -->
                        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-green-100 text-sm font-medium">Approved</p>
                                    <p class="text-3xl font-bold">{{ $statistics['approved'] ?? 0 }}</p>
                                </div>
                                <div class="text-4xl opacity-80">✅</div>
                            </div>
                        </div>

                        <!-- Total Days -->
                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-purple-100 text-sm font-medium">Total Days</p>
                                    <p class="text-3xl font-bold">{{ $statistics['total_days'] ?? 0 }}</p>
                                </div>
                                <div class="text-4xl opacity-80">📅</div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Leave Types Distribution -->
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                🏷️ Leave Types Distribution
                            </h3>
                            @if(!empty($statistics['by_type']) && count($statistics['by_type']) > 0)
                                <div class="space-y-3">
                                    @php
                                        $typeIcons = [
                                            'sick' => '🤒',
                                            'vacation' => '🏖️',
                                            'personal' => '👤',
                                            'emergency' => '🚨',
                                            'maternity' => '🤱',
                                            'paternity' => '👨👶',
                                            'bereavement' => '⚰️',
                                            'other' => '📝'
                                        ];
                                        $typeColors = [
                                            'sick' => 'bg-red-500',
                                            'vacation' => 'bg-blue-500',
                                            'personal' => 'bg-purple-500',
                                            'emergency' => 'bg-orange-500',
                                            'maternity' => 'bg-pink-500',
                                            'paternity' => 'bg-indigo-500',
                                            'bereavement' => 'bg-gray-500',
                                            'other' => 'bg-yellow-500'
                                        ];
                                        $total = array_sum($statistics['by_type']->toArray());
                                    @endphp
                                    @foreach($statistics['by_type'] as $type => $count)
                                        @php
                                            $percentage = $total > 0 ? round(($count / $total) * 100, 1) : 0;
                                            $icon = $typeIcons[$type] ?? '📝';
                                            $color = $typeColors[$type] ?? 'bg-gray-500';
                                        @endphp
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-3">
                                                <span class="text-lg">{{ $icon }}</span>
                                                <span class="text-sm font-medium text-gray-700">{{ ucfirst($type) }}</span>
                                            </div>
                                            <div class="flex items-center space-x-3">
                                                <div class="w-24 bg-gray-200 rounded-full h-2">
                                                    <div class="{{ $color }} h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                                </div>
                                                <span class="text-sm font-semibold text-gray-600 w-12 text-right">{{ $count }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 text-gray-500">
                                    <div class="text-4xl mb-2">📊</div>
                                    <p>No leave data available</p>
                                </div>
                            @endif
                        </div>

                        <!-- Pending Approvals -->
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                ⏳ Pending Approvals
                            </h3>
                            @if($pending_leaves && $pending_leaves->count() > 0)
                                <div class="space-y-3 max-h-80 overflow-y-auto">
                                    @foreach($pending_leaves as $leave)
                                        <div class="flex items-center justify-between p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                            <div class="flex-1">
                                                <div class="text-sm font-medium text-gray-900">{{ $leave->user->name ?? 'N/A' }}</div>
                                                <div class="text-xs text-gray-500">
                                                    {{ ucfirst($leave->leave_type) }} • 
                                                    {{ \Carbon\Carbon::parse($leave->start_date)->format('M d') }} - 
                                                    {{ \Carbon\Carbon::parse($leave->end_date)->format('M d') }}
                                                </div>
                                            </div>
                                            <div class="flex space-x-2">
                                                <a href="{{ route('dashboard_leave.get_leave_info', $leave->id) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                                    👁️
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @if($pending_leaves->count() >= 10)
                                    <div class="mt-3 text-center">
                                        <a href="{{ route('dashboard_leave.index', ['status' => 'pending']) }}" class="text-sm text-blue-600 hover:text-blue-800">
                                            View all pending leaves →
                                        </a>
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-8 text-gray-500">
                                    <div class="text-4xl mb-2">✅</div>
                                    <p>No pending approvals</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
                        <!-- Upcoming Leaves -->
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                📅 Upcoming Leaves (Next 7 Days)
                            </h3>
                            @if($upcoming_leaves && $upcoming_leaves->count() > 0)
                                <div class="space-y-3">
                                    @foreach($upcoming_leaves as $leave)
                                        <div class="flex items-center justify-between p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                            <div class="flex-1">
                                                <div class="text-sm font-medium text-gray-900">{{ $leave->user->name ?? 'N/A' }}</div>
                                                <div class="text-xs text-gray-500">
                                                    {{ ucfirst($leave->leave_type) }} • 
                                                    {{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }}
                                                    @if($leave->start_date != $leave->end_date)
                                                        - {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="text-xs text-blue-600 font-medium">
                                                {{ \Carbon\Carbon::parse($leave->start_date)->diffForHumans() }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 text-gray-500">
                                    <div class="text-4xl mb-2">📅</div>
                                    <p>No upcoming leaves</p>
                                </div>
                            @endif
                        </div>

                        <!-- Active Leaves -->
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                🏃 Currently on Leave
                            </h3>
                            @if($active_leaves && $active_leaves->count() > 0)
                                <div class="space-y-3">
                                    @foreach($active_leaves as $leave)
                                        <div class="flex items-center justify-between p-3 bg-green-50 border border-green-200 rounded-lg">
                                            <div class="flex-1">
                                                <div class="text-sm font-medium text-gray-900">{{ $leave->user->name ?? 'N/A' }}</div>
                                                <div class="text-xs text-gray-500">
                                                    {{ ucfirst($leave->leave_type) }} • 
                                                    Until {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}
                                                </div>
                                            </div>
                                            <div class="text-xs text-green-600 font-medium">
                                                Active
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 text-gray-500">
                                    <div class="text-4xl mb-2">🏢</div>
                                    <p>No one is currently on leave</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="mt-8">
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                🕒 Recent Leave Activity
                            </h3>
                            @if($recent_leaves && $recent_leaves->count() > 0)
                                <div class="space-y-3">
                                    @foreach($recent_leaves as $leave)
                                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-200">
                                            <div class="flex items-center space-x-4">
                                                <div class="flex-shrink-0">
                                                    @if($leave->status === 'approved')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            ✅ Approved
                                                        </span>
                                                    @elseif($leave->status === 'rejected')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                            ❌ Rejected
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            ⏳ Pending
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="flex-1">
                                                    <div class="text-sm font-medium text-gray-900">{{ $leave->user->name ?? 'N/A' }}</div>
                                                    <div class="text-xs text-gray-500">
                                                        {{ ucfirst($leave->leave_type) }} • 
                                                        {{ \Carbon\Carbon::parse($leave->start_date)->format('M d') }} - 
                                                        {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-3">
                                                <div class="text-xs text-gray-500">
                                                    {{ \Carbon\Carbon::parse($leave->created_at)->diffForHumans() }}
                                                </div>
                                                <a href="{{ route('dashboard_leave.get_leave_info', $leave->id) }}" class="text-blue-600 hover:text-blue-800">
                                                    👁️
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-4 text-center">
                                    <a href="{{ route('dashboard_leave.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                        View all leave requests →
                                    </a>
                                </div>
                            @else
                                <div class="text-center py-8 text-gray-500">
                                    <div class="text-4xl mb-2">📝</div>
                                    <p>No recent leave activity</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>