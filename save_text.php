<?php
header('Content-Type: application/json');

$uploadDir = 'uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['text']) || trim($input['text']) === '') {
    echo json_encode(['success' => false, 'message' => 'No text provided']);
    exit;
}

$text = trim($input['text']);
$filename = isset($input['filename']) ? trim($input['filename']) : '';

if ($filename === '') {
    $filename = 'message_' . date('Ymd_His') . '.txt';
} else {
    $filename = basename($filename);
    if (pathinfo($filename, PATHINFO_EXTENSION) === '') {
        $filename .= '.txt';
    }
}

$filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
if ($filename === '' || $filename === '.' || $filename === '..') {
    $filename = 'message_' . date('Ymd_His') . '.txt';
}

$filePath = $uploadDir . $filename;
$counter = 1;
while (file_exists($filePath)) {
    $pathInfo = pathinfo($filename);
    $filename = $pathInfo['filename'] . '_' . $counter . '.' . $pathInfo['extension'];
    $filePath = $uploadDir . $filename;
    $counter++;
}

if (file_put_contents($filePath, $text) !== false) {
    chmod($filePath, 0644);
    echo json_encode(['success' => true, 'message' => 'Text saved successfully', 'filename' => $filename]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save text file']);
}
