<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class QueryOptimizationService
{
    // Slow query threshold in milliseconds
    const SLOW_QUERY_THRESHOLD = 1000;
    const WARNING_QUERY_THRESHOLD = 500;

    protected array $queryLog = [];
    protected bool $enabled = false;

    /**
     * Enable query monitoring
     */
    public function enable(): void
    {
        $this->enabled = true;
        $this->setupQueryListener();
    }

    /**
     * Disable query monitoring
     */
    public function disable(): void
    {
        $this->enabled = false;
        DB::getEventDispatcher()?->forget('Illuminate\Database\Events\QueryExecuted');
    }

    /**
     * Setup database query listener
     */
    protected function setupQueryListener(): void
    {
        if (!$this->enabled) {
            return;
        }

        DB::listen(function ($query) {
            $this->logQuery($query);
        });
    }

    /**
     * Log query execution
     */
    protected function logQuery($query): void
    {
        $executionTime = $query->time;
        $sql = $query->sql;
        $bindings = $query->bindings;

        // Store in memory log
        $this->queryLog[] = [
            'sql' => $sql,
            'bindings' => $bindings,
            'time' => $executionTime,
            'timestamp' => now()->toDateTimeString(),
        ];

        // Log slow queries
        if ($executionTime >= self::SLOW_QUERY_THRESHOLD) {
            $this->logSlowQuery($sql, $bindings, $executionTime, 'critical');
        } elseif ($executionTime >= self::WARNING_QUERY_THRESHOLD) {
            $this->logSlowQuery($sql, $bindings, $executionTime, 'warning');
        }

        // Track query patterns
        $this->trackQueryPattern($sql, $executionTime);
    }

    /**
     * Log slow query
     */
    protected function logSlowQuery(string $sql, array $bindings, float $time, string $level): void
    {
        $context = [
            'sql' => $sql,
            'bindings' => $bindings,
            'execution_time' => $time . 'ms',
            'url' => request()->fullUrl(),
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
        ];

        if ($level === 'critical') {
            Log::critical('Slow query detected (>1000ms)', $context);

            // In production, send alert
            if (app()->environment('production')) {
                $this->sendSlowQueryAlert($context);
            }
        } else {
            Log::warning('Query performance warning (>500ms)', $context);
        }

        // Store slow query for analysis
        $this->storeSlowQueryForAnalysis($sql, $time);
    }

    /**
     * Track query patterns
     */
    protected function trackQueryPattern(string $sql, float $time): void
    {
        // Extract table name
        preg_match('/(?:from|into|update|join)\s+`?(\w+)`?/i', $sql, $matches);
        $table = $matches[1] ?? 'unknown';

        $cacheKey = "query.patterns.{$table}";
        $patterns = Cache::get($cacheKey, [
            'count' => 0,
            'total_time' => 0,
            'max_time' => 0,
            'queries' => [],
        ]);

        $patterns['count']++;
        $patterns['total_time'] += $time;
        $patterns['max_time'] = max($patterns['max_time'], $time);

        // Store last 10 queries for this table
        $patterns['queries'][] = [
            'sql' => $sql,
            'time' => $time,
        ];
        $patterns['queries'] = array_slice($patterns['queries'], -10);

        Cache::put($cacheKey, $patterns, now()->addHours(24));
    }

    /**
     * Store slow query for analysis
     */
    protected function storeSlowQueryForAnalysis(string $sql, float $time): void
    {
        $cacheKey = 'slow_queries_log';
        $slowQueries = Cache::get($cacheKey, []);

        $slowQueries[] = [
            'sql' => $sql,
            'time' => $time,
            'timestamp' => now()->toIso8601String(),
            'url' => request()->fullUrl(),
        ];

        // Keep only last 100 slow queries
        $slowQueries = array_slice($slowQueries, -100);

        Cache::put($cacheKey, $slowQueries, now()->addDays(7));
    }

    /**
     * Send slow query alert
     */
    protected function sendSlowQueryAlert(array $context): void
    {
        // This could be integrated with services like Sentry, Slack, etc.
        // For now, we'll just increment a counter
        Cache::increment('slow_queries_count_today', 1);
    }

    /**
     * Get query statistics
     */
    public function getQueryStats(): array
    {
        $totalQueries = count($this->queryLog);
        $totalTime = array_sum(array_column($this->queryLog, 'time'));
        $slowQueries = array_filter($this->queryLog, function ($query) {
            return $query['time'] >= self::SLOW_QUERY_THRESHOLD;
        });

        return [
            'total_queries' => $totalQueries,
            'total_time' => round($totalTime, 2) . 'ms',
            'average_time' => $totalQueries > 0 ? round($totalTime / $totalQueries, 2) . 'ms' : '0ms',
            'slow_queries_count' => count($slowQueries),
            'slow_queries' => array_values($slowQueries),
            'queries_by_table' => $this->getQueriesByTable(),
        ];
    }

    /**
     * Get queries grouped by table
     */
    protected function getQueriesByTable(): array
    {
        $byTable = [];

        foreach ($this->queryLog as $query) {
            preg_match('/(?:from|into|update|join)\s+`?(\w+)`?/i', $query['sql'], $matches);
            $table = $matches[1] ?? 'unknown';

            if (!isset($byTable[$table])) {
                $byTable[$table] = [
                    'count' => 0,
                    'total_time' => 0,
                ];
            }

            $byTable[$table]['count']++;
            $byTable[$table]['total_time'] += $query['time'];
        }

        // Calculate averages and sort by total time
        foreach ($byTable as $table => &$stats) {
            $stats['average_time'] = round($stats['total_time'] / $stats['count'], 2);
            $stats['total_time'] = round($stats['total_time'], 2);
        }

        uasort($byTable, function ($a, $b) {
            return $b['total_time'] <=> $a['total_time'];
        });

        return $byTable;
    }

    /**
     * Analyze query and suggest optimizations
     */
    public function analyzeQuery(string $sql): array
    {
        $suggestions = [];

        // Check for SELECT *
        if (preg_match('/select\s+\*/i', $sql)) {
            $suggestions[] = [
                'type' => 'optimization',
                'message' => 'Avoid using SELECT *. Specify only the columns you need.',
                'priority' => 'medium',
            ];
        }

        // Check for missing WHERE clause in UPDATE/DELETE
        if (preg_match('/(update|delete)\s+.*?\s+(?!.*where)/is', $sql)) {
            $suggestions[] = [
                'type' => 'danger',
                'message' => 'UPDATE/DELETE without WHERE clause detected! This could affect all rows.',
                'priority' => 'critical',
            ];
        }

        // Check for OR conditions (might benefit from UNION)
        if (preg_match_all('/\bor\b/i', $sql) >= 3) {
            $suggestions[] = [
                'type' => 'optimization',
                'message' => 'Multiple OR conditions detected. Consider using UNION or IN clause for better performance.',
                'priority' => 'low',
            ];
        }

        // Check for subqueries
        if (preg_match('/select.*?select/is', $sql)) {
            $suggestions[] = [
                'type' => 'optimization',
                'message' => 'Subquery detected. Consider using JOIN if possible for better performance.',
                'priority' => 'medium',
            ];
        }

        // Check for LIKE with leading wildcard
        if (preg_match('/like\s+[\'"]%/i', $sql)) {
            $suggestions[] = [
                'type' => 'optimization',
                'message' => 'LIKE with leading wildcard (\'%term\') cannot use indexes. Consider full-text search.',
                'priority' => 'medium',
            ];
        }

        // Check for missing LIMIT in SELECT
        if (preg_match('/select\s+.*?\s+from/is', $sql) && !preg_match('/limit\s+\d+/i', $sql)) {
            $suggestions[] = [
                'type' => 'optimization',
                'message' => 'Consider adding LIMIT clause to prevent loading too many rows.',
                'priority' => 'low',
            ];
        }

        return $suggestions;
    }

    /**
     * Get slow queries log
     */
    public function getSlowQueriesLog(): array
    {
        return Cache::get('slow_queries_log', []);
    }

    /**
     * Get query patterns
     */
    public function getQueryPatterns(): array
    {
        $tables = ['users', 'profiles', 'photos', 'likes', 'user_matches', 'messages', 'conversations'];
        $patterns = [];

        foreach ($tables as $table) {
            $cacheKey = "query.patterns.{$table}";
            $pattern = Cache::get($cacheKey);

            if ($pattern) {
                $patterns[$table] = $pattern;
            }
        }

        return $patterns;
    }

    /**
     * Clear query logs
     */
    public function clearLogs(): void
    {
        $this->queryLog = [];
        Cache::forget('slow_queries_log');
    }

    /**
     * Generate performance report
     */
    public function generateReport(): array
    {
        $slowQueries = $this->getSlowQueriesLog();
        $patterns = $this->getQueryPatterns();

        return [
            'summary' => [
                'total_slow_queries_logged' => count($slowQueries),
                'tables_tracked' => count($patterns),
                'generated_at' => now()->toDateTimeString(),
            ],
            'slow_queries' => array_slice($slowQueries, -20), // Last 20
            'patterns' => $patterns,
            'recommendations' => $this->generateRecommendations($patterns),
        ];
    }

    /**
     * Generate optimization recommendations
     */
    protected function generateRecommendations(array $patterns): array
    {
        $recommendations = [];

        foreach ($patterns as $table => $pattern) {
            $avgTime = $pattern['total_time'] / $pattern['count'];

            if ($avgTime > 100) {
                $recommendations[] = [
                    'table' => $table,
                    'message' => "Average query time is {$avgTime}ms. Consider adding indexes or optimizing queries.",
                    'priority' => $avgTime > 500 ? 'high' : 'medium',
                ];
            }

            if ($pattern['count'] > 100) {
                $recommendations[] = [
                    'table' => $table,
                    'message' => "{$pattern['count']} queries executed. Consider caching results.",
                    'priority' => 'medium',
                ];
            }
        }

        return $recommendations;
    }

    /**
     * Get current request query count
     */
    public function getRequestQueryCount(): int
    {
        return count($this->queryLog);
    }

    /**
     * Check if request has too many queries (N+1 problem indicator)
     */
    public function checkForNPlusOne(): bool
    {
        return $this->getRequestQueryCount() > 30;
    }
}
