<?php

/**
 * Performance Configuration
 *
 * This file contains performance optimization settings
 * for the application including caching, compression,
 * and query optimization strategies.
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    */
    'cache' => [
        // Cache duration for tags list (1 hour)
        'tags_ttl' => 3600,

        // Cache duration for users list (30 minutes)
        'users_ttl' => 1800,

        // Cache duration for projects list (1 hour)
        'projects_ttl' => 3600,

        // Cache duration for API responses (5 minutes)
        'api_ttl' => 300,
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Optimization
    |--------------------------------------------------------------------------
    */
    'database' => [
        // Enable query optimization
        'optimize_queries' => true,

        // Eager loading by default
        'eager_loading' => true,

        // Items per page for pagination
        'pagination' => [
            'issues' => 10,
            'comments' => 10,
            'tags' => 20,
            'projects' => 15,
        ],

        // Select only needed columns
        'selective_columns' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Asset Configuration
    |--------------------------------------------------------------------------
    */
    'assets' => [
        // Enable asset minification
        'minify' => true,

        // Enable source maps in development
        'source_maps' => env('APP_ENV') === 'development',

        // Asset cache busting
        'cache_busting' => true,

        // Max age for static assets (1 year)
        'max_age' => 31536000,
    ],

    /*
    |--------------------------------------------------------------------------
    | Compression Configuration
    |--------------------------------------------------------------------------
    */
    'compression' => [
        // Enable Gzip compression
        'gzip' => true,

        // Gzip compression level (1-9, 6 is default)
        'gzip_level' => 6,

        // Enable Brotli compression for modern browsers
        'brotli' => true,

        // Minimum file size for compression (1KB)
        'min_size' => 1024,
    ],

    /*
    |--------------------------------------------------------------------------
    | Browser Caching
    |--------------------------------------------------------------------------
    */
    'browser_cache' => [
        // Cache control for static assets
        'static' => 'public, max-age=31536000, immutable',

        // Cache control for dynamic content
        'dynamic' => 'no-cache, must-revalidate, private, max-age=0',

        // Cache control for API responses
        'api' => 'no-cache, must-revalidate, private',

        // Enable ETag headers
        'etag' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | API Optimization
    |--------------------------------------------------------------------------
    */
    'api' => [
        // Enable response pagination
        'pagination' => true,

        // Items per request
        'per_page' => 10,

        // Enable request debouncing
        'debounce' => true,

        // Debounce delay (milliseconds)
        'debounce_delay' => 300,
    ],

    /*
    |--------------------------------------------------------------------------
    | Monitoring & Profiling
    |--------------------------------------------------------------------------
    */
    'monitoring' => [
        // Enable query logging in development
        'log_queries' => env('APP_DEBUG', false),

        // Enable performance monitoring
        'monitor_performance' => true,

        // Log slow queries (milliseconds)
        'slow_query_threshold' => 100,
    ],

    /*
    |--------------------------------------------------------------------------
    | Production Settings
    |--------------------------------------------------------------------------
    */
    'production' => [
        // Enable all caching in production
        'cache_enabled' => env('APP_ENV') === 'production',

        // Use Redis for caching if available
        'use_redis' => env('REDIS_HOST') !== null,

        // Enable Gzip compression
        'compression_enabled' => true,

        // Enable browser caching
        'browser_cache_enabled' => true,
    ],
];
