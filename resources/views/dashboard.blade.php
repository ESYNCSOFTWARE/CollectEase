 @extends('layouts.app')

@section('module')
    {{ 'Dashboard' }}
@endsection

@section('subdomain')
    {{ 'Core Insights' }}
@endsection


@section('css')
<link rel="stylesheet" href="{{ asset('css/assignments.css') }}">
    <style>
  .dashboard {
   --primary: #4361ee;
   --secondary: #3a0ca3;
   --success: #4cc9f0;
   --warning: #f72585;
   --info: #7209b7;
   --light-bg: #f8fafc;
   --dark-text: #1e293b;
   --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
   --hover-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
}
        
        body {
            font-family: 'Inter', sans-serif;
            background: var(--light-bg);
            color: var(--dark-text);
        }
        
    
        .nav-item {
            transition: all 0.2s ease;
            border-radius: 8px;
            padding: 12px 16px;
            margin: 4px 0;
        }
        
        .nav-item:hover {
            background: rgba(255, 255, 255, 0.15);
        }
        
        .nav-item.active {
            background: rgba(255, 255, 255, 0.2);
            font-weight: 600;
        }
        
        .dashboard-card {
            background: white;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        .dashboard-card:hover {
            box-shadow: var(--hover-shadow);
            transform: translateY(-2px);
        }
        
        .stat-card {
            padding: 20px;
            border-left: 4px solid var(--primary);
        }
        
        .stat-card.warning {
            border-left-color: var(--warning);
        }
        
        .stat-card.success {
            border-left-color: var(--success);
        }
        
        .stat-card.info {
            border-left-color: var(--info);
        }
        
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
        
        .progress-bar {
            height: 8px;
            border-radius: 4px;
            background: #e2e8f0;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            border-radius: 4px;
            background: linear-gradient(90deg, var(--primary) 0%, var(--secondary) 100%);
        }
        
        .notification-dot {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 8px;
            height: 8px;
            background: var(--warning);
            border-radius: 50%;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 64px;
            }
            
            .sidebar .nav-text {
                display: none;
            }
        }
    </style>
@endsection

 @section('content')
 






<div class="flex-1 overflow-auto p-6">

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="dashboard-card stat-card">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500">Total Portfolio</p>
                    <h2 class="text-2xl font-bold">
                    RM {{ number_format(\App\Models\CaseFile::sum('amount_due'),) }}
                    </h2>
                </div>
                <div class="p-3 rounded-lg bg-blue-100 text-blue-600">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>
        </div>
        
        <div class="dashboard-card stat-card warning">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500">Overdue Money</p>
                    <h2 class="text-2xl font-bold">
                    RM {{ number_format(\App\Models\CaseFile::overdue()->sum('amount_due')) }}
                    </h2>
                </div>
                <div class="p-3 rounded-lg bg-red-100 text-red-600">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
            </div>
        </div>
        
        <div class="dashboard-card stat-card success">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500">Recovered This Month</p>
                    <h2 class="text-2xl font-bold">
                        RM {{ number_format(\App\Models\CaseFile::where('status','Paid')
                            ->whereMonth('updated_at', now()->month)
                            ->sum('amount_due'),) }}
                    </h2>
                </div>
                <div class="p-3 rounded-lg bg-green-100 text-green-600">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
        
        <div class="dashboard-card stat-card info">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500">Success Rate</p>
                    <h2 class="text-2xl font-bold">
                        {{
                          number_format(
                            (\App\Models\CaseFile::where('status','Paid')->count()
                            / max(\App\Models\CaseFile::count(),1)) * 100,1
                          )
                        }}%
                    </h2>
                </div>
                <div class="p-3 rounded-lg bg-purple-100 text-purple-600">
                    <i class="fas fa-percent"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts Row 1 -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="dashboard-card p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-semibold">Monthly Recovery Trend</h3>
            </div>
            <div class="chart-container">
                <canvas id="recoveryTrendChart"></canvas>
            </div>
        </div>
        
        <div class="dashboard-card p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-semibold">Debt Distribution by Age</h3>
            </div>
            <div class="chart-container">
                <canvas id="debtDistributionChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Charts Row 2 -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Collection Performance -->
        <div class="dashboard-card p-6">
            <h3 class="font-semibold mb-6">Collection Performance</h3>
            <div class="space-y-5">
                @php
                    $buckets = [
                        'Current' => \App\Models\CaseFile::whereDate('due_date','>=',now())->count(),
                        '30-60 Days' => \App\Models\CaseFile::whereBetween('due_date',[now()->subDays(60),now()->subDays(31)])->count(),
                        '60-90 Days' => \App\Models\CaseFile::whereBetween('due_date',[now()->subDays(90),now()->subDays(61)])->count(),
                        '>90 Days' => \App\Models\CaseFile::where('due_date','<',now()->subDays(90))->count(),
                    ];
                @endphp
                @foreach($buckets as $label=>$val)
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span>{{ $label }}</span>
                        <span>{{ $val }}</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $val>0?min(100,$val):0 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Top Collectors -->
        <div class="dashboard-card p-6">
            <h3 class="font-semibold mb-6">Top Collectors</h3>
            <div class="space-y-4">
                @foreach($topCollectors as $c)
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                            <span class="text-blue-600 font-medium">
                                {{ strtoupper(substr($c->assignedCollector->name ?? 'NA',0,2)) }}
                            </span>
                        </div>
                        <div>
                            <p class="font-medium">{{ $c->assignedCollector->name ?? 'Unassigned' }}</p>
                            <p class="text-xs text-gray-500">{{ $c->assignedCollector?->cases()->count() }} cases</p>
                        </div>
                    </div>
                    <span class="font-semibold text-green-500">
                        RM {{ number_format($c->recovered,2) }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Recent Activities -->
        <div class="dashboard-card p-6">
            <h3 class="font-semibold mb-6">Recent Activities</h3>
            <div class="space-y-4">
                @foreach($recentActivities as $activity)
                <div class="flex">
                    <div class="mr-3 mt-1">
                        <div class="w-2 h-2 rounded-full {{ $activity['color'] }}"></div>
                    </div>
                    <div>
                        <p class="font-medium">{{ $activity['title'] }}</p>
                        <p class="text-xs text-gray-500">{{ $activity['details'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Recovery Trend from Controller
const recoveryTrend = @json($recoveryTrend);
new Chart(document.getElementById('recoveryTrendChart'), {
    type: 'line',
    data: {
        labels: Object.keys(recoveryTrend),
        datasets: [{
            label: 'Recovered Amount',
            data: Object.values(recoveryTrend),
            borderColor: '#4361ee',
            backgroundColor: 'rgba(67,97,238,0.1)',
            fill: true,
            tension: 0.4,
            borderWidth: 3
        }]
    },
    options: { plugins:{legend:{display:false}} }
});

// Debt Distribution
const debtBuckets = @json($debtBuckets);
new Chart(document.getElementById('debtDistributionChart'), {
    type: 'bar',
    data: {
        labels: Object.keys(debtBuckets),
        datasets: [{
            label: 'Amount (RM)',
            data: Object.values(debtBuckets),
            backgroundColor: [
                'rgba(67,97,238,0.8)',
                'rgba(76,201,240,0.8)',
                'rgba(114,9,183,0.8)',
                'rgba(247,37,133,0.8)',
                'rgba(58,12,163,0.8)'
            ],
            borderRadius: 6
        }]
    },
    options: { plugins:{legend:{display:false}} }
});
</script>
@endsection