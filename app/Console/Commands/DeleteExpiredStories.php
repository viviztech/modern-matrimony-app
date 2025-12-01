<?php

namespace App\Console\Commands;

use App\Models\Story;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DeleteExpiredStories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stories:delete-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete expired stories (older than 24 hours)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Deleting expired stories...');

        $expiredStories = Story::expired()->get();

        if ($expiredStories->isEmpty()) {
            $this->info('No expired stories found.');
            return Command::SUCCESS;
        }

        $count = 0;
        foreach ($expiredStories as $story) {
            // Delete media files
            if ($story->media_url) {
                $path = str_replace('/storage/', '', $story->media_url);
                Storage::disk('public')->delete($path);
            }

            $story->delete();
            $count++;
        }

        $this->info("Deleted {$count} expired " . ($count === 1 ? 'story' : 'stories') . '.');

        return Command::SUCCESS;
    }
}
