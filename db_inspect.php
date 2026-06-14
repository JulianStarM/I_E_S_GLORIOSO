<?php
// Temporary script to inspect database structure
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== TABLES IN biblioteca_escolar ===\n";
$tables = DB::select('SHOW TABLES');
foreach ($tables as $table) {
    $name = array_values((array)$table)[0];
    echo "\n--- TABLE: {$name} ---\n";
    $columns = DB::select("SHOW COLUMNS FROM `{$name}`");
    foreach ($columns as $col) {
        echo "  {$col->Field} | {$col->Type} | " . ($col->Null === 'YES' ? 'NULL' : 'NOT NULL') . " | {$col->Key} | {$col->Default}\n";
    }
}
