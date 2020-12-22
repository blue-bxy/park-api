<?php

namespace App\Providers;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\ServiceProvider;

class SqlLoggerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // 监视执行的每条SQL，写入一个独立的日志文件。
        $sqlEnabled = $this->app->config->get('lingtong.sql');
        if ($sqlEnabled) {
            logs('sql')->info('============ URL: ' . request()->fullUrl() . ' METHOD: '.request()->method().' ===============');
            \DB::listen(function (QueryExecuted $query) {

                $sqlWithPlaceholders = str_replace(['%', '?'], ['%%', '%s'], $query->sql);

                $bindings = $query->connection->prepareBindings($query->bindings);
                $pdo = $query->connection->getPdo();
                $realSql = vsprintf($sqlWithPlaceholders, array_map([$pdo, 'quote'], $bindings));
                $duration = $this->formatDuration($query->time / 1000);

                logs('sql')->debug(sprintf('[%s] %s', $duration, $realSql));
            });
        }
    }

    /**
     * @param $seconds
     * @return string
     */
    private function formatDuration($seconds)
    {
        if ($seconds < 0.001) {
            return round($seconds * 1000000) . 'μs';
        } elseif ($seconds < 1) {
            return round($seconds * 1000, 2) . 'ms';
        }

        return round($seconds, 2) . 's';
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
