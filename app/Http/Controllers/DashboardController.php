<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\CaseFile;
use App\Models\Assignment;
use App\Models\CaseStatusHistory;
use App\Models\Escalation;

class DashboardController extends Controller
{
    public function index()
    {
        /**
         * 📈 Monthly Recovery Trend (last 6 months, always show all months)
         */
        $months = collect(range(0, 5))
            ->map(fn($i) => now()->subMonths($i)->format('M'))
            ->reverse()
            ->values();

        // Generate last 6 months with year (e.g. 2025-03, 2025-04 ...)
$months = collect(range(0,5))
    ->map(fn($i) => now()->subMonths($i)->format('Y-m'))
    ->reverse()
    ->values();

// Pull data grouped by Year-Month
$recoveryRaw = CaseFile::selectRaw("DATE_FORMAT(updated_at, '%Y-%m') as ym, SUM(amount_due) as total")
    ->where('status', 'Paid')
    ->where('updated_at', '>=', now()->subMonths(6))
    ->groupBy('ym')
    ->pluck('total','ym');

// Final dataset with zeros for missing months
$recoveryTrend = $months->mapWithKeys(function($ym) use ($recoveryRaw) {
    // Format for chart label (e.g. Aug-2025)
    $label = Carbon::createFromFormat('Y-m',$ym)->format('M-Y');
    return [$label => $recoveryRaw[$ym] ?? 0];
});


        /**
         * 📊 Debt Distribution by Age Buckets
         */
        $debtBuckets = [
            'Current' => CaseFile::whereDate('due_date', '>=', now())->sum('amount_due'),
            '1-30 Days' => CaseFile::whereBetween('due_date', [now()->subDays(30), now()->subDay()])->sum('amount_due'),
            '31-60 Days' => CaseFile::whereBetween('due_date', [now()->subDays(60), now()->subDays(31)])->sum('amount_due'),
            '61-90 Days' => CaseFile::whereBetween('due_date', [now()->subDays(90), now()->subDays(61)])->sum('amount_due'),
            '90+ Days' => CaseFile::where('due_date', '<', now()->subDays(90))->sum('amount_due'),
        ];

        /**
         * 🏆 Top Collectors (by recovered amount)
         */
        $topCollectors = CaseFile::select('assigned_collector_id', DB::raw('SUM(amount_due) as recovered'))
            ->where('status', 'Paid')
            ->groupBy('assigned_collector_id')
            ->with('assignedCollector')
            ->orderByDesc('recovered')
            ->take(5)
            ->get();

        /**
         * 🔔 Recent Activities (Assignments, Status changes, Escalations)
         */
        $recentActivities = [];

        $latestAssignments = Assignment::with('toCollector')
            ->latest()->take(3)->get()
            ->map(fn($a) => [
                'title' => "Case reassigned to " . ($a->toCollector?->name ?? 'Unknown'),
                'details' => "Case #" . $a->case_id . " • " . $a->created_at->diffForHumans(),
                'color' => "bg-blue-500"
            ]);

        $latestStatus = CaseStatusHistory::latest()->take(3)->get()
            ->map(fn($s) => [
                'title' => "Case status changed to " . $s->new_status,
                'details' => "Case #" . $s->case_id . " • " . $s->created_at->diffForHumans(),
                'color' => "bg-green-500"
            ]);

        $latestEsc = Escalation::latest()->take(3)->get()
            ->map(fn($e) => [
                'title' => "Case escalated to " . $e->escalated_to,
                'details' => "Case #" . $e->case_id . " • " . $e->created_at->diffForHumans(),
                'color' => "bg-red-500"
            ]);

        $recentActivities = $latestAssignments
            ->merge($latestStatus)
            ->merge($latestEsc)
            ->sortByDesc('details')
            ->take(5);

        /**
         * Return view
         */
        return view('dashboard', compact(
            'recoveryTrend',
            'debtBuckets',
            'topCollectors',
            'recentActivities'
        ));
    }
}
