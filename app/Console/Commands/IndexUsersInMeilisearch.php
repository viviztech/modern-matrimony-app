<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use MeiliSearch\Client;

class IndexUsersInMeilisearch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meilisearch:index-users
                            {--fresh : Delete existing index and recreate}
                            {--chunk=100 : Number of users to process at once}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Index all users in Meilisearch with proper configuration';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting Meilisearch indexing...');

        try {
            // Get Meilisearch client
            $client = app(Client::class);
            $indexName = config('scout.prefix') . 'users';

            // Delete and recreate index if --fresh flag is used
            if ($this->option('fresh')) {
                $this->warn('Deleting existing index...');
                try {
                    $client->deleteIndex($indexName);
                    $this->info('Existing index deleted.');
                    sleep(1); // Give Meilisearch time to process
                } catch (\Exception $e) {
                    $this->warn('No existing index to delete or error occurred: ' . $e->getMessage());
                }
            }

            // Get or create index
            $this->info('Getting/Creating index...');
            $index = $client->index($indexName);

            // Configure filterable attributes
            $this->info('Configuring filterable attributes...');
            $filterableAttributes = [
                'id',
                'gender',
                'age',
                'city',
                'state',
                'country',
                'is_active',
                'is_premium',
                'height',
                'education',
                'occupation',
                'religion',
                'caste',
                'mother_tongue',
                'marital_status',
                'have_children',
                'diet',
                'drinking',
                'smoking',
                'body_type',
                'annual_income_range',
                'has_video_intro',
                'email_verified',
                'phone_verified',
                'video_verified',
                'is_verified',
                'verification_count',
                'has_photos',
                'profile_completion',
                'is_online',
                'last_active_at',
            ];

            $index->updateFilterableAttributes($filterableAttributes);
            $this->info('Filterable attributes configured: ' . count($filterableAttributes));

            // Configure sortable attributes
            $this->info('Configuring sortable attributes...');
            $sortableAttributes = [
                'created_at',
                'last_active_at',
                'age',
                'height',
                'profile_completion',
                'verification_count',
            ];

            $index->updateSortableAttributes($sortableAttributes);
            $this->info('Sortable attributes configured: ' . count($sortableAttributes));

            // Configure searchable attributes
            $this->info('Configuring searchable attributes...');
            $searchableAttributes = [
                'name',
                'bio',
                'looking_for',
                'occupation',
                'company',
                'education',
                'field_of_study',
                'city',
                'state',
                'interests',
                'hobbies',
            ];

            $index->updateSearchableAttributes($searchableAttributes);
            $this->info('Searchable attributes configured: ' . count($searchableAttributes));

            // Wait for settings to be applied
            $this->info('Waiting for settings to be applied...');
            sleep(2);

            // Index users
            $this->info('Starting to index users...');
            $chunkSize = (int) $this->option('chunk');

            $query = User::with(['profile', 'photos'])
                ->active()
                ->whereHas('profile');

            $total = $query->count();
            $this->info("Found {$total} users to index.");

            if ($total === 0) {
                $this->warn('No users found to index.');
                return self::SUCCESS;
            }

            $bar = $this->output->createProgressBar($total);
            $bar->start();

            $indexed = 0;
            $failed = 0;

            $query->chunk($chunkSize, function ($users) use (&$indexed, &$failed, $bar) {
                try {
                    // Use Scout's searchable() method to index
                    $users->searchable();
                    $indexed += $users->count();
                    $bar->advance($users->count());
                } catch (\Exception $e) {
                    $failed += $users->count();
                    Log::error('Failed to index user chunk: ' . $e->getMessage());
                    $this->error("\nFailed to index chunk: " . $e->getMessage());
                }
            });

            $bar->finish();
            $this->newLine(2);

            // Summary
            $this->info('Indexing completed!');
            $this->table(
                ['Metric', 'Count'],
                [
                    ['Total Users', $total],
                    ['Successfully Indexed', $indexed],
                    ['Failed', $failed],
                ]
            );

            // Get index stats
            try {
                $stats = $index->stats();
                $this->info("Meilisearch Index Stats:");
                $this->line("  - Total documents: " . ($stats['numberOfDocuments'] ?? 0));
                $this->line("  - Index size: " . $this->formatBytes($stats['indexSize'] ?? 0));
            } catch (\Exception $e) {
                $this->warn('Could not retrieve index stats: ' . $e->getMessage());
            }

            Log::info("Meilisearch indexing completed. Indexed: {$indexed}, Failed: {$failed}");

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Indexing failed: ' . $e->getMessage());
            Log::error('Meilisearch indexing failed: ' . $e->getMessage());
            return self::FAILURE;
        }
    }

    /**
     * Format bytes to human-readable size.
     */
    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
