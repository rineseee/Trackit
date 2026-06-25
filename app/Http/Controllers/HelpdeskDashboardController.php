<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\View\View;

class HelpdeskDashboardController extends Controller
{
    public function index(): View
    {
        $totalIssues = Issue::count();
        $openIssues = Issue::where('status', 'open')->count();
        $inProgressIssues = Issue::where('status', 'in_progress')->count();
        $closedIssues = Issue::where('status', 'closed')->count();
        $highPriorityIssues = Issue::where('priority', 'high')->count();

        // Priority breakdown
        $lowPriorityIssues = Issue::where('priority', 'low')->count();
        $mediumPriorityIssues = Issue::where('priority', 'medium')->count();

        // Calculate percentages
        $issuesTrendPercent = $this->calculateTrendPercent($totalIssues, 'this_month');
        $resolutionRate = $totalIssues > 0 ? round(($closedIssues / $totalIssues) * 100, 1) : 0;

        // Monthly trend for last 6 months
        $monthlyTrend = $this->getMonthlyTrend();

        // Recent issues
        $recentIssues = Issue::with(['project', 'assignedTo'])
            ->latest()
            ->limit(10)
            ->get();

        return view('dashboard.helpdesk', [
            'totalIssues' => $totalIssues,
            'openIssues' => $openIssues,
            'inProgressIssues' => $inProgressIssues,
            'closedIssues' => $closedIssues,
            'highPriorityIssues' => $highPriorityIssues,
            'lowPriorityIssues' => $lowPriorityIssues,
            'mediumPriorityIssues' => $mediumPriorityIssues,
            'issuesTrendPercent' => $issuesTrendPercent,
            'resolutionRate' => $resolutionRate,
            'monthlyTrend' => $monthlyTrend,
            'recentIssues' => $recentIssues,
        ]);
    }

    private function getMonthlyTrend(): array
    {
        $trend = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = Issue::whereBetween('created_at', [
                $date->startOfMonth(),
                $date->endOfMonth()
            ])->count();

            $trend[$date->format('M Y')] = $count;
        }

        return $trend;
    }

    private function calculateTrendPercent($current, $period = 'this_month'): int
    {
        if ($period === 'this_month') {
            $lastMonthStart = now()->subMonth()->startOfMonth();
            $lastMonthEnd = now()->subMonth()->endOfMonth();

            $lastMonthCount = Issue::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();

            if ($lastMonthCount === 0) {
                return $current > 0 ? 100 : 0;
            }

            return round((($current - $lastMonthCount) / $lastMonthCount) * 100);
        }

        return 0;
    }
}
