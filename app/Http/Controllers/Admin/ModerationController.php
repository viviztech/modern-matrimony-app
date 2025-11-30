<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use App\Models\PhotoVerification;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModerationController extends Controller
{
    /**
     * Show admin moderation dashboard.
     */
    public function index()
    {
        $stats = [
            'pending_reports' => Report::pending()->count(),
            'pending_photo_verifications' => PhotoVerification::where('verification_status', 'flagged')->count(),
            'flagged_photos' => PhotoVerification::inappropriate()->count(),
            'total_reports' => Report::count(),
            'resolved_reports_today' => Report::resolved()->whereDate('reviewed_at', today())->count(),
        ];

        $recentReports = Report::with(['reporter', 'reportedUser', 'reviewer'])
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.moderation.index', compact('stats', 'recentReports'));
    }

    /**
     * Show photo verifications that need review.
     */
    public function photos(Request $request)
    {
        $status = $request->get('status', 'flagged');

        $verifications = PhotoVerification::with(['photo', 'user'])
            ->where('verification_status', $status)
            ->latest()
            ->paginate(20);

        return view('admin.moderation.photos', compact('verifications', 'status'));
    }

    /**
     * Approve a photo verification.
     */
    public function approvePhoto(PhotoVerification $verification)
    {
        $verification->update([
            'verification_status' => 'passed',
            'verified_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Photo approved successfully');
    }

    /**
     * Reject a photo verification.
     */
    public function rejectPhoto(Request $request, PhotoVerification $verification)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $verification->markAsFailed($request->reason);

        // Optionally delete the photo
        if ($request->delete_photo) {
            $verification->photo->delete();
        }

        return redirect()->back()->with('success', 'Photo rejected successfully');
    }

    /**
     * Show all reports.
     */
    public function reports(Request $request)
    {
        $status = $request->get('status', 'pending');
        $reason = $request->get('reason');

        $query = Report::with(['reporter', 'reportedUser', 'reviewer']);

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        if ($reason) {
            $query->where('reason', $reason);
        }

        $reports = $query->latest()->paginate(20);

        return view('admin.moderation.reports', compact('reports', 'status', 'reason'));
    }

    /**
     * Show single report details.
     */
    public function showReport(Report $report)
    {
        $report->load(['reporter', 'reportedUser', 'reviewer', 'reportable']);

        return view('admin.moderation.report-details', compact('report'));
    }

    /**
     * Resolve a report.
     */
    public function resolveReport(Request $request, Report $report)
    {
        $request->validate([
            'notes' => 'nullable|string|max:1000',
            'action' => 'required|in:warn,suspend,ban,nothing',
        ]);

        // Take action on reported user if needed
        if ($request->action === 'ban' && $report->reportedUser) {
            $report->reportedUser->update([
                'is_active' => false,
                'banned_at' => now(),
                'ban_reason' => $request->notes ?? 'Violating community guidelines',
            ]);
        } elseif ($request->action === 'suspend' && $report->reportedUser) {
            $report->reportedUser->update([
                'is_active' => false,
                'suspended_until' => now()->addDays(7),
                'suspension_reason' => $request->notes ?? 'Temporary suspension',
            ]);
        }

        $report->markAsResolved(Auth::user(), $request->notes);

        return redirect()->route('admin.moderation.reports')
            ->with('success', 'Report resolved successfully');
    }

    /**
     * Dismiss a report.
     */
    public function dismissReport(Request $request, Report $report)
    {
        $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        $report->markAsDismissed(Auth::user(), $request->notes);

        return redirect()->route('admin.moderation.reports')
            ->with('success', 'Report dismissed');
    }

    /**
     * Show user management.
     */
    public function users(Request $request)
    {
        $query = User::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->status === 'banned') {
            $query->whereNotNull('banned_at');
        } elseif ($request->status === 'suspended') {
            $query->whereNotNull('suspended_until')
                ->where('suspended_until', '>', now());
        } elseif ($request->status === 'active') {
            $query->where('is_active', true)
                ->whereNull('banned_at');
        }

        $users = $query->latest()->paginate(20);

        return view('admin.moderation.users', compact('users'));
    }

    /**
     * Show user details.
     */
    public function showUser(User $user)
    {
        $user->load(['photos', 'profile', 'socialAccounts']);

        $stats = [
            'reports_received' => Report::where('reported_user_id', $user->id)->count(),
            'reports_made' => Report::where('reporter_id', $user->id)->count(),
            'photos_flagged' => PhotoVerification::where('user_id', $user->id)
                ->inappropriate()
                ->count(),
        ];

        return view('admin.moderation.user-details', compact('user', 'stats'));
    }

    /**
     * Ban a user.
     */
    public function banUser(Request $request, User $user)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $user->update([
            'is_active' => false,
            'banned_at' => now(),
            'ban_reason' => $request->reason,
        ]);

        return redirect()->back()->with('success', 'User banned successfully');
    }

    /**
     * Unban a user.
     */
    public function unbanUser(User $user)
    {
        $user->update([
            'is_active' => true,
            'banned_at' => null,
            'ban_reason' => null,
            'suspended_until' => null,
            'suspension_reason' => null,
        ]);

        return redirect()->back()->with('success', 'User unbanned successfully');
    }

    /**
     * Suspend a user.
     */
    public function suspendUser(Request $request, User $user)
    {
        $request->validate([
            'days' => 'required|integer|min:1|max:365',
            'reason' => 'required|string|max:500',
        ]);

        $user->update([
            'is_active' => false,
            'suspended_until' => now()->addDays($request->days),
            'suspension_reason' => $request->reason,
        ]);

        return redirect()->back()->with('success', 'User suspended for ' . $request->days . ' days');
    }

    /**
     * Get activity logs (placeholder for future implementation).
     */
    public function activityLogs()
    {
        return view('admin.moderation.activity-logs');
    }

    /**
     * Bulk actions on reports.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:resolve,dismiss,delete',
            'report_ids' => 'required|array',
            'report_ids.*' => 'exists:reports,id',
        ]);

        $reports = Report::whereIn('id', $request->report_ids)->get();

        foreach ($reports as $report) {
            if ($request->action === 'resolve') {
                $report->markAsResolved(Auth::user());
            } elseif ($request->action === 'dismiss') {
                $report->markAsDismissed(Auth::user());
            } elseif ($request->action === 'delete') {
                $report->delete();
            }
        }

        return redirect()->back()->with('success', count($reports) . ' reports processed');
    }
}
