<?php
header('Content-Type: application/json');

$uploadDir = 'uploads/';
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['file'])) {
    echo json_encode(['success' => false, 'message' => 'No file specified']);
    exit;
}

$fileName = $input['file'];
$fileName = basename($fileName);

$filePath = $uploadDir . $fileName;

if (!file_exists($filePath) || !is_file($filePath)) {
    echo json_encode(['success' => false, 'message' => 'File not found']);
    exit;
}

$realPath = realpath($filePath);
$realUploadDir = realpath($uploadDir);

if ($realPath === false || strpos($realPath, $realUploadDir) !== 0) {
    echo json_encode(['success' => false, 'message' => 'Access denied']);
    exit;
}

if (unlink($filePath)) {
    echo json_encode(['success' => true, 'message' => 'File deleted successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete file']);
}
?>
