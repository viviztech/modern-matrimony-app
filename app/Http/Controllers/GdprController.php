<?php

namespace App\Http\Controllers;

use App\Services\GdprService;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class GdprController extends Controller
{
    protected GdprService $gdprService;

    public function __construct(GdprService $gdprService)
    {
        $this->gdprService = $gdprService;
        $this->middleware('auth');
    }

    /**
     * Show data export page
     */
    public function exportData()
    {
        $user = auth()->user();

        return view('gdpr.export-data', compact('user'));
    }

    /**
     * Generate and download data export
     */
    public function downloadData(Request $request)
    {
        $request->validate([
            'format' => 'required|in:json,pdf,zip',
        ]);

        $user = auth()->user();
        $format = $request->input('format');

        try {
            switch ($format) {
                case 'json':
                    $data = $this->gdprService->exportAsJson($user);
                    $filename = 'user-data-export-' . $user->id . '-' . now()->format('Y-m-d-His') . '.json';

                    // Log the export
                    if (class_exists(\App\Services\AuditLogService::class)) {
                        app(AuditLogService::class)->log('data_export_json', $user->id);
                    }

                    return Response::make($data, 200, [
                        'Content-Type' => 'application/json',
                        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                    ]);

                case 'pdf':
                    $path = $this->gdprService->exportAsPdf($user);
                    $fullPath = Storage::disk('local')->path($path);

                    // Log the export
                    if (class_exists(\App\Services\AuditLogService::class)) {
                        app(AuditLogService::class)->log('data_export_pdf', $user->id);
                    }

                    return response()->download($fullPath)->deleteFileAfterSend(true);

                case 'zip':
                    $path = $this->gdprService->exportAsZip($user);
                    $fullPath = Storage::disk('local')->path($path);

                    // Log the export
                    if (class_exists(\App\Services\AuditLogService::class)) {
                        app(AuditLogService::class)->log('data_export_zip', $user->id);
                    }

                    return response()->download($fullPath)->deleteFileAfterSend(true);
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to export data: ' . $e->getMessage());
        }
    }

    /**
     * Show account deletion request page
     */
    public function showDeletionRequest()
    {
        $user = auth()->user();

        return view('gdpr.request-deletion', compact('user'));
    }

    /**
     * Request account deletion
     */
    public function requestDeletion(Request $request)
    {
        $request->validate([
            'reason' => 'nullable|string|max:1000',
            'confirmation' => 'required|accepted',
        ]);

        $user = auth()->user();

        // Check if deletion is already requested
        if ($user->deletion_requested_at) {
            return back()->with('info', 'Account deletion is already scheduled for ' . $user->deletion_scheduled_for->format('M d, Y'));
        }

        try {
            $this->gdprService->requestDeletion($user, $request->input('reason'));

            return redirect()->route('gdpr.deletion-pending')
                ->with('success', 'Your account deletion has been scheduled. You have ' .
                    config('gdpr.deletion_grace_period_days', 30) . ' days to cancel this request.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to request deletion: ' . $e->getMessage());
        }
    }

    /**
     * Show deletion pending page
     */
    public function deletionPending()
    {
        $user = auth()->user();

        if (!$user->deletion_requested_at) {
            return redirect()->route('profile.edit');
        }

        $daysRemaining = $this->gdprService->getDaysRemainingBeforeDeletion($user);

        return view('gdpr.deletion-pending', compact('user', 'daysRemaining'));
    }

    /**
     * Cancel account deletion
     */
    public function cancelDeletion()
    {
        $user = auth()->user();

        if (!$this->gdprService->canCancelDeletion($user)) {
            return back()->with('error', 'Cannot cancel deletion at this time.');
        }

        try {
            $this->gdprService->cancelDeletion($user);

            return redirect()->route('profile.edit')
                ->with('success', 'Your account deletion has been cancelled.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to cancel deletion: ' . $e->getMessage());
        }
    }

    /**
     * Confirm and immediately delete account
     */
    public function confirmDeletion(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password',
            'confirmation' => 'required|accepted',
        ]);

        $user = auth()->user();

        try {
            // Export data before deletion
            $exportPath = $this->gdprService->exportAsZip($user);

            // Send final email with data export
            $user->notify(new \App\Notifications\AccountDeleted($exportPath));

            // Logout user
            auth()->logout();

            // Delete account
            $this->gdprService->deleteAccount($user);

            return redirect()->route('home')
                ->with('success', 'Your account has been permanently deleted. We have sent you a final data export via email.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete account: ' . $e->getMessage());
        }
    }

    /**
     * Show data portability page
     */
    public function dataPortability()
    {
        $user = auth()->user();

        return view('gdpr.data-portability', compact('user'));
    }

    /**
     * Transfer data to another service
     */
    public function transferData(Request $request)
    {
        $request->validate([
            'service' => 'required|in:google,facebook,microsoft',
            'consent' => 'required|accepted',
        ]);

        $user = auth()->user();

        // Generate portable data format
        $data = $this->gdprService->collectUserData($user);

        // Log the transfer
        if (class_exists(\App\Services\AuditLogService::class)) {
            app(AuditLogService::class)->log('data_portability', $user->id, [
                'service' => $request->input('service'),
            ]);
        }

        // This would integrate with the target service's API
        // For now, we'll just return the data in a standard format

        return back()->with('success', 'Data portability feature coming soon. Please download your data as JSON for now.');
    }

    /**
     * Show consent management page
     */
    public function consentManagement()
    {
        $user = auth()->user();

        return view('gdpr.consent-management', compact('user'));
    }

    /**
     * Update consent preferences
     */
    public function updateConsent(Request $request)
    {
        $request->validate([
            'marketing_emails' => 'boolean',
            'data_processing' => 'boolean',
            'profiling' => 'boolean',
            'third_party_sharing' => 'boolean',
        ]);

        $user = auth()->user();

        $preferences = $user->notification_preferences ?? [];
        $preferences['gdpr_consent'] = [
            'marketing_emails' => $request->boolean('marketing_emails'),
            'data_processing' => $request->boolean('data_processing'),
            'profiling' => $request->boolean('profiling'),
            'third_party_sharing' => $request->boolean('third_party_sharing'),
            'updated_at' => now()->toIso8601String(),
        ];

        $user->update(['notification_preferences' => $preferences]);

        // Log consent change
        if (class_exists(\App\Services\AuditLogService::class)) {
            app(AuditLogService::class)->log('consent_updated', $user->id, $preferences['gdpr_consent']);
        }

        return back()->with('success', 'Your consent preferences have been updated.');
    }
}
