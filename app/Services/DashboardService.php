<?php

namespace App\Services;

use App\Models\Issue;
use App\Models\Project;
use Illuminate\Support\Collection;

class DashboardService
{
    public static function getStatistics(): array
    {
        return [
            'totalProjects' => Project::count(),
            'totalIssues' => Issue::count(),
            'openIssues' => Issue::where('status', 'open')->count(),
            'inProgressIssues' => Issue::where('status', 'in_progress')->count(),
            'closedIssues' => Issue::where('status', 'closed')->count(),
        ];
    }

    public static function getIssuesByStatus(): array
    {
        return Issue::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }

    public static function getIssuesByPriority(): array
    {
        return Issue::selectRaw('priority, COUNT(*) as count')
            ->groupBy('priority')
            ->pluck('count', 'priority')
            ->toArray();
    }

    public static function getIssuesByProject(int $limit = 6): Collection
    {
        return Project::withCount('issues')
            ->where('issues_count', '>', 0)
            ->orderByDesc('issues_count')
            ->limit($limit)
            ->get();
    }

    public static function getRecentIssues(int $limit = 8): Collection
    {
        return Issue::with('project', 'members')
            ->latest()
            ->limit($limit)
            ->get();
    }

    public static function getRecentProjects(int $limit = 5): Collection
    {
        return Project::latest()
            ->limit($limit)
            ->get();
    }

    public static function getChartData(): array
    {
        $stats = self::getStatistics();
        $byPriority = self::getIssuesByPriority();
        $byProject = self::getIssuesByProject();

        return [
            'status' => json_encode([
                'labels' => ['Open', 'In Progress', 'Closed'],
                'datasets' => [[
                    'label' => 'Issues by Status',
                    'data' => [
                        $stats['openIssues'],
                        $stats['inProgressIssues'],
                        $stats['closedIssues'],
                    ],
                    'backgroundColor' => ['#3b82f6', '#f59e0b', '#10b981'],
                    'borderColor' => ['#1e40af', '#d97706', '#059669'],
                    'borderWidth' => 2,
                ]],
            ]),
            'priority' => json_encode([
                'labels' => ['Low', 'Medium', 'High'],
                'datasets' => [[
                    'label' => 'Issues by Priority',
                    'data' => [
                        $byPriority['low'] ?? 0,
                        $byPriority['medium'] ?? 0,
                        $byPriority['high'] ?? 0,
                    ],
                    'backgroundColor' => ['#93c5fd', '#fcd34d', '#fca5a5'],
                    'borderColor' => ['#1e40af', '#d97706', '#dc2626'],
                    'borderWidth' => 2,
                ]],
            ]),
            'projects' => json_encode([
                'labels' => $byProject->pluck('name')->toArray(),
                'datasets' => [[
                    'label' => 'Issues per Project',
                    'data' => $byProject->pluck('issues_count')->toArray(),
                    'backgroundColor' => '#6366f1',
                    'borderColor' => '#4f46e5',
                    'borderWidth' => 2,
                ]],
            ]),
        ];
    }

    public static function getHealthCheck(): array
    {
        $stats = self::getStatistics();
        $closureRate = $stats['totalIssues'] > 0
            ? round(($stats['closedIssues'] / $stats['totalIssues']) * 100, 1)
            : 0;

        return [
            'totalIssues' => $stats['totalIssues'],
            'openIssues' => $stats['openIssues'],
            'closedIssues' => $stats['closedIssues'],
            'closureRate' => $closureRate,
            'inProgressRate' => $stats['totalIssues'] > 0
                ? round(($stats['inProgressIssues'] / $stats['totalIssues']) * 100, 1)
                : 0,
            'health' => match (true) {
                $closureRate >= 80 => 'excellent',
                $closureRate >= 60 => 'good',
                $closureRate >= 40 => 'fair',
                default => 'needs_attention',
            },
        ];
    }

    public static function getStatusColor(string $status): string
    {
        return match ($status) {
            'open' => 'blue',
            'in_progress' => 'amber',
            'closed' => 'green',
            default => 'slate',
        };
    }

    public static function getPriorityColor(string $priority): string
    {
        return match ($priority) {
            'low' => 'blue',
            'medium' => 'orange',
            'high' => 'red',
            default => 'slate',
        };
    }

    public static function formatStatistic(int $value): string
    {
        if ($value >= 1000000) {
            return round($value / 1000000, 1) . 'M';
        } elseif ($value >= 1000) {
            return round($value / 1000, 1) . 'K';
        }

        return (string) $value;
    }
}
