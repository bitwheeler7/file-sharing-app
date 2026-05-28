<?php
$uploadDir = 'uploads/';

if (!isset($_GET['file'])) {
    http_response_code(400);
    die('No file specified');
}

$fileName = $_GET['file'];
$fileName = basename($fileName);

$filePath = $uploadDir . $fileName;

if (!file_exists($filePath) || !is_file($filePath)) {
    http_response_code(404);
    die('File not found');
}

$realPath = realpath($filePath);
$realUploadDir = realpath($uploadDir);

if ($realPath === false || strpos($realPath, $realUploadDir) !== 0) {
    http_response_code(403);
    die('Access denied');
}

header('Content-Description: File Transfer');
$mimeType = mime_content_type($filePath) ?: 'application/octet-stream';
$preview = isset($_GET['preview']) && $_GET['preview'] === '1';

header('Content-Type: ' . $mimeType);
header(($preview ? 'Content-Disposition: inline; ' : 'Content-Disposition: attachment; ') . 'filename="' . basename($filePath) . '"');
header('Content-Length: ' . filesize($filePath));
header('Cache-Control: public, must-revalidate');
header('Pragma: public');

readfile($filePath);
exit;
?>
