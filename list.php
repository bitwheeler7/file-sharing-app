<?php
header('Content-Type: application/json');

$uploadDir = 'uploads/';
$files = [];

if (is_dir($uploadDir)) {
    $items = scandir($uploadDir);
    
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        
        $filePath = $uploadDir . $item;
        if (is_file($filePath)) {
            $files[] = [
                'name' => $item,
                'size' => filesize($filePath),
                'modified' => filemtime($filePath),
                'path' => $filePath
            ];
        }
    }
    
    usort($files, function($a, $b) {
        return $b['modified'] - $a['modified'];
    });
}

echo json_encode(['success' => true, 'files' => $files, 'count' => count($files)]);
?>
