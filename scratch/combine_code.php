<?php
$rootDir = realpath(__DIR__ . '/../');
$outputFile = $rootDir . '/views/all_program.txt';

$allowedExtensions = ['php', 'sql', 'json', 'env', 'js', 'css'];
$ignoredDirs = ['.git', 'vendor', 'uploads', 'assets', 'scratch', 'tests'];

function scanFiles($dir, &$results = [], $ignoredDirs = [], $allowedExtensions = []) {
    $files = scandir($dir);
    foreach ($files as $key => $value) {
        $path = $dir . DIRECTORY_SEPARATOR . $value;
        if (!is_dir($path)) {
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            if (in_array($ext, $allowedExtensions) || in_array(basename($path), ['.env', '.gitignore'])) {
                if (basename($path) !== 'all_program.txt') {
                    $results[] = $path;
                }
            }
        } else if ($value != "." && $value != "..") {
            if (!in_array($value, $ignoredDirs)) {
                scanFiles($path, $results, $ignoredDirs, $allowedExtensions);
            }
        }
    }
    return $results;
}

echo "Scanning source code files in: $rootDir\n";
$allFiles = scanFiles($rootDir, $results, $ignoredDirs, $allowedExtensions);

$handle = fopen($outputFile, 'w');
if (!$handle) {
    die("Gagal membuka file output: $outputFile\n");
}

foreach ($allFiles as $file) {
    $relPath = str_replace($rootDir . DIRECTORY_SEPARATOR, '', $file);
    echo "Processing file: $relPath\n";
    
    fwrite($handle, "\n" . str_repeat("=", 80) . "\n");
    fwrite($handle, "FILE: $relPath\n");
    fwrite($handle, str_repeat("=", 80) . "\n\n");
    
    $content = file_get_contents($file);
    fwrite($handle, $content);
    fwrite($handle, "\n");
}

fclose($handle);
echo "Sukses membuat views/all_program.txt dengan total " . count($allFiles) . " file.\n";
?>
