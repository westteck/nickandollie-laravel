<?php
// One-shot script to backfill the migrations table for the 11 newly-added
// schema migrations. The actual tables already exist in prod (from a manual
// import of the legacy schema). This script records the migrations as
// already-run so future `migrate` calls don't try to recreate them.
//
// Run as: sudo -u www php scripts/backfill_migrations.php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$migrations = [
    '2026_06_14_190000_create_photos_table',
    '2026_06_14_190100_create_contests_table',
    '2026_06_14_190200_create_contest_entries_table',
    '2026_06_14_190300_create_comments_table',
    '2026_06_14_190400_create_favorites_table',
    '2026_06_14_190500_create_ratings_table',
    '2026_06_14_190600_create_theme_settings_table',
    '2026_06_14_190700_create_address_book_tables',
    '2026_06_14_190800_create_lookup_options_table',
    '2026_06_14_190900_create_site_pages_table',
    '2026_06_14_191000_create_site_settings_table',
];

$batch = (int) (\DB::table('migrations')->max('batch') ?: 0) + 1;
$inserted = 0;
foreach ($migrations as $m) {
    $exists = \DB::table('migrations')->where('migration', $m)->exists();
    if ($exists) {
        echo "  skip (already): {$m}\n";
        continue;
    }
    \DB::table('migrations')->insert([
        'migration' => $m,
        'batch' => $batch,
    ]);
    echo "  added: {$m}\n";
    $inserted++;
}
echo "\nDone. Inserted: {$inserted}, batch: {$batch}\n";
