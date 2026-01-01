<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Exception;

class HealthCheckController extends Controller
{
    /**
     * Comprehensive health check for the application
     *
     * Checks:
     * - Database connectivity
     * - Redis connectivity
     * - Cache functionality
     * - Queue connectivity
     * - Disk space
     * - Application status
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $checks = [
            'database' => $this->checkDatabase(),
            'redis' => $this->checkRedis(),
            'cache' => $this->checkCache(),
            'queue' => $this->checkQueue(),
            'disk' => $this->checkDiskSpace(),
        ];

        $allHealthy = collect($checks)->every(fn($check) => $check['status'] === 'healthy');

        return response()->json([
            'status' => $allHealthy ? 'healthy' : 'unhealthy',
            'timestamp' => now()->toIso8601String(),
            'application' => config('app.name'),
            'environment' => app()->environment(),
            'checks' => $checks,
        ], $allHealthy ? 200 : 503);
    }

    /**
     * Simple health check endpoint (for load balancers)
     *
     * @return JsonResponse
     */
    public function simple(): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Check database connectivity
     *
     * @return array
     */
    protected function checkDatabase(): array
    {
        try {
            DB::connection()->getPdo();
            $time = DB::connection()->getDatabaseName();

            return [
                'status' => 'healthy',
                'message' => 'Database connection successful',
                'database' => $time,
                'driver' => config('database.default'),
            ];
        } catch (Exception $e) {
            return [
                'status' => 'unhealthy',
                'message' => 'Database connection failed',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check Redis connectivity
     *
     * @return array
     */
    protected function checkRedis(): array
    {
        try {
            Redis::ping();

            return [
                'status' => 'healthy',
                'message' => 'Redis connection successful',
            ];
        } catch (Exception $e) {
            return [
                'status' => 'unhealthy',
                'message' => 'Redis connection failed',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check cache functionality
     *
     * @return array
     */
    protected function checkCache(): array
    {
        try {
            $key = 'health_check_' . time();
            $value = 'test';

            Cache::put($key, $value, 60);
            $retrieved = Cache::get($key);
            Cache::forget($key);

            if ($retrieved === $value) {
                return [
                    'status' => 'healthy',
                    'message' => 'Cache read/write successful',
                    'driver' => config('cache.default'),
                ];
            }

            return [
                'status' => 'unhealthy',
                'message' => 'Cache read/write failed',
            ];
        } catch (Exception $e) {
            return [
                'status' => 'unhealthy',
                'message' => 'Cache check failed',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check queue connectivity
     *
     * @return array
     */
    protected function checkQueue(): array
    {
        try {
            $connection = config('queue.default');

            // For database queue, check database connection
            if ($connection === 'database') {
                DB::table('jobs')->count();
            }

            // For Redis queue, check Redis connection
            if ($connection === 'redis') {
                Redis::connection('default')->ping();
            }

            return [
                'status' => 'healthy',
                'message' => 'Queue connection successful',
                'driver' => $connection,
            ];
        } catch (Exception $e) {
            return [
                'status' => 'unhealthy',
                'message' => 'Queue check failed',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check disk space
     *
     * @return array
     */
    protected function checkDiskSpace(): array
    {
        try {
            $path = storage_path();
            $totalSpace = disk_total_space($path);
            $freeSpace = disk_free_space($path);
            $usedSpace = $totalSpace - $freeSpace;
            $usedPercentage = ($usedSpace / $totalSpace) * 100;

            $status = 'healthy';
            $message = 'Disk space is adequate';

            if ($usedPercentage > 90) {
                $status = 'unhealthy';
                $message = 'Disk space is critically low';
            } elseif ($usedPercentage > 80) {
                $status = 'warning';
                $message = 'Disk space is running low';
            }

            return [
                'status' => $status,
                'message' => $message,
                'total' => $this->formatBytes($totalSpace),
                'free' => $this->formatBytes($freeSpace),
                'used' => $this->formatBytes($usedSpace),
                'used_percentage' => round($usedPercentage, 2) . '%',
            ];
        } catch (Exception $e) {
            return [
                'status' => 'unhealthy',
                'message' => 'Disk space check failed',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Format bytes to human-readable format
     *
     * @param int $bytes
     * @return string
     */
    protected function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}
