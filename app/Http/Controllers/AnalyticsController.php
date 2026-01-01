<?php

namespace App\Http\Controllers;

use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    protected $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    /**
     * User Analytics Dashboard
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $days = $request->get('days', 30);

        $metrics = $this->analyticsService->getUserEngagementMetrics($user, $days);
        $sources = $this->analyticsService->getActivitySources($user, $days);

        return view('analytics.index', compact('metrics', 'sources', 'days'));
    }

    /**
     * Profile Views - Who viewed my profile
     */
    public function profileViews(Request $request)
    {
        $user = Auth::user();
        $days = $request->get('days', 30);

        $viewers = $this->analyticsService->getProfileViewers($user, $days);
        $sources = $this->analyticsService->getActivitySources($user, $days);

        return view('analytics.profile-views', compact('viewers', 'sources', 'days'));
    }

    /**
     * Engagement Statistics API endpoint
     */
    public function engagementStats(Request $request)
    {
        $user = Auth::user();
        $days = $request->get('days', 30);

        $metrics = $this->analyticsService->getUserEngagementMetrics($user, $days);

        return response()->json($metrics);
    }

    /**
     * Admin Analytics Dashboard
     */
    public function adminDashboard(Request $request)
    {
        // Ensure user is admin
        if (!Auth::user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        $startDate = $request->get('start_date', now()->subDays(30)->toDateString());
        $endDate = $request->get('end_date', now()->toDateString());

        $metrics = $this->analyticsService->getAdminMetrics($startDate, $endDate);
        $conversionFunnel = $this->analyticsService->getConversionFunnel();
        $demographics = $this->analyticsService->getUserDemographics();
        $peakHours = $this->analyticsService->getPeakUsageHours();

        return view('admin.analytics.dashboard', compact(
            'metrics',
            'conversionFunnel',
            'demographics',
            'peakHours',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Admin User Metrics
     */
    public function userMetrics(Request $request)
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        $startDate = $request->get('start_date', now()->subDays(30)->toDateString());
        $endDate = $request->get('end_date', now()->toDateString());

        $metrics = $this->analyticsService->getAdminMetrics($startDate, $endDate);
        $demographics = $this->analyticsService->getUserDemographics();

        return view('admin.analytics.users', compact('metrics', 'demographics', 'startDate', 'endDate'));
    }

    /**
     * Admin Engagement Metrics
     */
    public function engagementMetrics(Request $request)
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        $startDate = $request->get('start_date', now()->subDays(30)->toDateString());
        $endDate = $request->get('end_date', now()->toDateString());

        $metrics = $this->analyticsService->getAdminMetrics($startDate, $endDate);
        $peakHours = $this->analyticsService->getPeakUsageHours(30);

        return view('admin.analytics.engagement', compact('metrics', 'peakHours', 'startDate', 'endDate'));
    }

    /**
     * Admin Conversion Funnel
     */
    public function conversionFunnel(Request $request)
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        $conversionFunnel = $this->analyticsService->getConversionFunnel();

        return response()->json($conversionFunnel);
    }

    /**
     * Admin Revenue Metrics
     */
    public function revenueMetrics(Request $request)
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        $startDate = $request->get('start_date', now()->subDays(30)->toDateString());
        $endDate = $request->get('end_date', now()->toDateString());

        $metrics = $this->analyticsService->getAdminMetrics($startDate, $endDate);

        return view('admin.analytics.revenue', compact('metrics', 'startDate', 'endDate'));
    }

    /**
     * Admin Cohort Analysis
     */
    public function cohortAnalysis(Request $request)
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        $cohortDate = $request->get('cohort_date', now()->subMonths(3)->toDateString());

        $cohortData = $this->analyticsService->getCohortAnalysis($cohortDate);

        return view('admin.analytics.cohorts', compact('cohortData', 'cohortDate'));
    }

    /**
     * Export analytics data as CSV
     */
    public function export(Request $request)
    {
        $user = Auth::user();
        $days = $request->get('days', 30);

        $metrics = $this->analyticsService->getUserEngagementMetrics($user, $days);

        $filename = "analytics_export_" . now()->format('Y-m-d') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($metrics) {
            $file = fopen('php://output', 'w');

            // Headers
            fputcsv($file, ['Date', 'Profile Views', 'Profile Viewed By', 'Likes Sent', 'Likes Received',
                'Messages Sent', 'Messages Received', 'Matches', 'Searches', 'Time Spent (min)', 'Engagement Score']);

            // Data
            foreach ($metrics['daily_metrics'] as $daily) {
                fputcsv($file, [
                    $daily['date'],
                    $daily['profile_views'],
                    $daily['profile_viewed_by'],
                    $daily['likes_sent'],
                    $daily['likes_received'],
                    $daily['messages_sent'],
                    $daily['messages_received'],
                    $daily['matches'],
                    $daily['searches'],
                    $daily['time_spent_minutes'],
                    $daily['engagement_score'],
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Clear analytics cache (admin only)
     */
    public function clearCache(Request $request)
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        $this->analyticsService->clearCache();

        return response()->json(['message' => 'Analytics cache cleared successfully']);
    }
}
