<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\GdprService;
use Illuminate\Console\Command;

class ProcessAccountDeletions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'accounts:process-deletions {--force : Skip confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process scheduled account deletions';

    protected GdprService $gdprService;

    /**
     * Create a new command instance.
     */
    public function __construct(GdprService $gdprService)
    {
        parent::__construct();
        $this->gdprService = $gdprService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Processing scheduled account deletions...');

        // Get users scheduled for deletion
        $usersToDelete = User::whereNotNull('deletion_scheduled_for')
            ->where('deletion_scheduled_for', '<=', now())
            ->get();

        if ($usersToDelete->isEmpty()) {
            $this->info('No accounts scheduled for deletion.');
            return Command::SUCCESS;
        }

        $this->info('Found ' . $usersToDelete->count() . ' accounts to delete.');

        if (!$this->option('force')) {
            if (!$this->confirm('Do you want to proceed with deletion?')) {
                $this->info('Deletion cancelled.');
                return Command::SUCCESS;
            }
        }

        $deleted = 0;
        $failed = 0;

        foreach ($usersToDelete as $user) {
            try {
                $this->info("Deleting account for user ID {$user->id} ({$user->email})...");

                // Send final notification
                $user->notify(new \App\Notifications\AccountDeleted());

                // Delete the account
                $this->gdprService->deleteAccount($user);

                $deleted++;
                $this->info("Successfully deleted account for user ID {$user->id}");
            } catch (\Exception $e) {
                $failed++;
                $this->error("Failed to delete account for user ID {$user->id}: " . $e->getMessage());
            }
        }

        $this->info("\nDeletion Summary:");
        $this->info("Successfully deleted: {$deleted}");
        $this->info("Failed: {$failed}");

        // Send reminder emails to users with upcoming deletions (7 days before)
        $this->sendReminderEmails();

        return Command::SUCCESS;
    }

    /**
     * Send reminder emails to users with upcoming deletions
     */
    protected function sendReminderEmails(): void
    {
        $this->info("\nSending reminder emails...");

        $usersWithUpcomingDeletion = User::whereNotNull('deletion_scheduled_for')
            ->whereBetween('deletion_scheduled_for', [
                now()->addDays(7),
                now()->addDays(8),
            ])
            ->get();

        $reminded = 0;

        foreach ($usersWithUpcomingDeletion as $user) {
            try {
                $daysRemaining = now()->diffInDays($user->deletion_scheduled_for);
                $user->notify(new \App\Notifications\AccountDeletionReminder($daysRemaining));
                $reminded++;
            } catch (\Exception $e) {
                $this->error("Failed to send reminder to user ID {$user->id}: " . $e->getMessage());
            }
        }

        if ($reminded > 0) {
            $this->info("Sent {$reminded} reminder emails.");
        } else {
            $this->info("No reminder emails to send.");
        }
    }
}
