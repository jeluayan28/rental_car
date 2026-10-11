<?php

// Never print PHP notices/deprecations into the page (the runtime ships with display_errors on).
ini_set('display_errors', '0');
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

// Vercel entry point. The deployment filesystem is read-only except /tmp, so Laravel's
// writable directories (set via env in vercel.json) live there and are created on each cold start.
foreach (['framework/views', 'framework/cache', 'framework/sessions', 'logs'] as $dir) {
    if (! is_dir("/tmp/storage/$dir")) {
        mkdir("/tmp/storage/$dir", 0755, true);
    }
}

require __DIR__.'/../public/index.php';
