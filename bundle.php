<?php
// Root Directory: C:\laragon\www\tracked
// Run in Terminal / Command Prompt: php bundle.php

$targetDirectories = [
    'app/Http/Controllers',
    'app/Models',
    'app/Services',
    'app/Http/Middleware',
    'app/Http/Requests',
    'app/Imports',
    'app/Livewire',
    'database/migrations',
    'resources/views',
    'routes'
];

$outputFile = 'tracked_full_system_blueprint.txt';

$compiledContent = "==================================================\n";
$compiledContent .= "TRACKED FULL SYSTEM BLUEPRINT (BACKEND + FRONTEND + LIVEWIRE)\n";
$compiledContent .= "Generated on: " . date('Y-m-d H:i:s') . "\n";
$compiledContent .= "==================================================\n\n";

$fileList = [];
$fileContents = "";

foreach ($targetDirectories as $dir) {
    if (!is_dir($dir)) {
        continue;
    }

    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS)
    );

    foreach ($files as $file) {
        if ($file->isFile()) {
            $filename = $file->getFilename();

            // Capture all .php and .blade.php files
            if (str_ends_with($filename, '.php')) {
                $filePath = $file->getPathname();
                $cleanPath = str_replace('\\', '/', $filePath);

                $fileList[] = $cleanPath;

                $fileContents .= "==================================================\n";
                $fileContents .= "FILE: " . $cleanPath . "\n";
                $fileContents .= "==================================================\n\n";
                $fileContents .= file_get_contents($filePath);
                $fileContents .= "\n\n";
            }
        }
    }
}

// File Index Header
$compiledContent .= "FILE INDEX (" . count($fileList) . " Total Files Scanned):\n";
foreach ($fileList as $index => $path) {
    $compiledContent .= "[" . ($index + 1) . "] " . $path . "\n";
}
$compiledContent .= "\n==================================================\n\n";

// Append Content
$compiledContent .= $fileContents;

file_put_contents($outputFile, $compiledContent);

echo "\n✨ Complete Full-Stack Scan Finished!";
echo "\nTotal files bundled (Backend + Blade Views + Livewire): " . count($fileList);
echo "\nSaved output to: " . $outputFile . "\n\n";
