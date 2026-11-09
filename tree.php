<?php

function buildTree($dir, $prefix = '', $excludes = ['vendor', 'node_modules', '.git', 'storage']) {
    $output = '';
    $items = scandir($dir);
    $items = array_diff($items, ['.', '..']);
    $items = array_filter($items, function ($item) use ($excludes) {
        return !in_array($item, $excludes);
    });
    $items = array_values($items);

    $count = count($items);

    foreach ($items as $index => $item) {
        $path = $dir . DIRECTORY_SEPARATOR . $item;
        $isLast = ($index === $count - 1);
        $connector = $isLast ? '└── ' : '├── ';

        $output .= $prefix . $connector . $item . PHP_EOL;

        if (is_dir($path)) {
            $newPrefix = $prefix . ($isLast ? '    ' : '│   ');
            $output .= buildTree($path, $newPrefix, $excludes);
        }
    }

    return $output;
}

$root = __DIR__;
$result = $root . PHP_EOL . buildTree($root);

// Print to terminal
echo $result;

// Save to file directly (UTF-8, no PowerShell redirect issues)
file_put_contents(__DIR__ . '/structure.txt', $result);

echo PHP_EOL . "Saved to structure.txt" . PHP_EOL;
