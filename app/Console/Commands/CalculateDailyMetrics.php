<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\UserActivity;
use App\Models\ProfileView;
use App\Models\EngagementMetric;
use Carbon\Carbon;

class CalculateDailyMetrics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'analytics:calculate-daily-metrics {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate daily engagement metrics for all users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = $this->argument('date') ? Carbon::parse($this->argument('date')) : Carbon::yesterday();
        $dateString = $date->toDateString();

        $this->info("Calculating daily metrics for {$dateString}...");

        // Get all users who had any activity on this date
        $activeUserIds = UserActivity::whereDate('created_at', $dateString)
            ->distinct('user_id')
            ->pluck('user_id');

        $this->info("Found {$activeUserIds->count()} active users");

        $progressBar = $this->output->createProgressBar($activeUserIds->count());
        $progressBar->start();

        foreach ($activeUserIds as $userId) {
            $this->calculateMetricsForUser($userId, $dateString);
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();

        $this->info("Daily metrics calculation completed!");
    }

    /**
     * Calculate metrics for a specific user and date.
     */
    protected function calculateMetricsForUser($userId, $date)
    {
        $startOfDay = Carbon::parse($date)->startOfDay();
        $endOfDay = Carbon::parse($date)->endOfDay();

        // Profile views given by user
        $profileViewsCount = ProfileView::where('viewer_id', $userId)
            ->whereBetween('viewed_at', [$startOfDay, $endOfDay])
            ->count();

        // Profile views received by user
        $profileViewedByCount = ProfileView::where('profile_id', $userId)
            ->whereBetween('viewed_at', [$startOfDay, $endOfDay])
            ->count();

        // Likes sent
        $likesSentCount = UserActivity::where('user_id', $userId)
            ->where('activity_type', UserActivity::TYPE_LIKE_SENT)
            ->whereBetween('created_at', [$startOfDay, $endOfDay])
            ->count();

        // Likes received
        $likesReceivedCount = UserActivity::where('user_id', $userId)
            ->where('activity_type', UserActivity::TYPE_LIKE_RECEIVED)
            ->whereBetween('created_at', [$startOfDay, $endOfDay])
            ->count();

        // Messages sent
        $messagesSentCount = UserActivity::where('user_id', $userId)
            ->where('activity_type', UserActivity::TYPE_MESSAGE_SENT)
            ->whereBetween('created_at', [$startOfDay, $endOfDay])
            ->count();

        // Messages received
        $messagesReceivedCount = UserActivity::where('user_id', $userId)
            ->where('activity_type', UserActivity::TYPE_MESSAGE_RECEIVED)
            ->whereBetween('created_at', [$startOfDay, $endOfDay])
            ->count();

        // Matches created
        $matchesCount = UserActivity::where('user_id', $userId)
            ->where('activity_type', UserActivity::TYPE_MATCH_CREATED)
            ->whereBetween('created_at', [$startOfDay, $endOfDay])
            ->count();

        // Searches performed
        $searchCount = UserActivity::where('user_id', $userId)
            ->where('activity_type', UserActivity::TYPE_SEARCH_PERFORMED)
            ->whereBetween('created_at', [$startOfDay, $endOfDay])
            ->count();

        // Login count
        $loginCount = UserActivity::where('user_id', $userId)
            ->where('activity_type', UserActivity::TYPE_LOGIN)
            ->whereBetween('created_at', [$startOfDay, $endOfDay])
            ->count();

        // Time spent (sum of all activity durations)
        $timeSpentSeconds = EngagementMetric::where('user_id', $userId)
            ->where('date', $date)
            ->value('time_spent_seconds') ?? 0;

        // Create or update engagement metric
        EngagementMetric::updateOrCreate(
            [
                'user_id' => $userId,
                'date' => $date,
            ],
            [
                'profile_views_count' => $profileViewsCount,
                'profile_viewed_by_count' => $profileViewedByCount,
                'likes_sent_count' => $likesSentCount,
                'likes_received_count' => $likesReceivedCount,
                'messages_sent_count' => $messagesSentCount,
                'messages_received_count' => $messagesReceivedCount,
                'matches_count' => $matchesCount,
                'search_count' => $searchCount,
                'time_spent_seconds' => $timeSpentSeconds,
                'login_count' => $loginCount,
            ]
        );
    }
}
