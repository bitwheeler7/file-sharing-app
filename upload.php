<?php
header('Content-Type: application/json');

$uploadDir = 'uploads/';
$maxFileSize = 100 * 1024 * 1024; // 100 MB

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

if (!isset($_FILES['file'])) {
    echo json_encode(['success' => false, 'message' => 'No file provided']);
    exit;
}

$file = $_FILES['file'];
$fileName = $file['name'];
$fileTmpPath = $file['tmp_name'];
$fileError = $file['error'];
$fileSize = $file['size'];

if ($fileError !== UPLOAD_ERR_OK) {
    $errorMessages = [
        UPLOAD_ERR_INI_SIZE => 'File exceeds maximum upload size',
        UPLOAD_ERR_FORM_SIZE => 'File exceeds form maximum size',
        UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
        UPLOAD_ERR_NO_FILE => 'No file was uploaded',
        UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary directory',
        UPLOAD_ERR_CANT_WRITE => 'Cannot write file to disk',
        UPLOAD_ERR_EXTENSION => 'File upload blocked by extension',
    ];
    echo json_encode(['success' => false, 'message' => $errorMessages[$fileError] ?? 'Unknown upload error']);
    exit;
}

if ($fileSize > $maxFileSize) {
    echo json_encode(['success' => false, 'message' => 'File size exceeds 100 MB limit']);
    exit;
}

$fileName = basename($fileName);
$fileName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $fileName);

$filePath = $uploadDir . $fileName;
$counter = 1;
while (file_exists($filePath)) {
    $pathInfo = pathinfo($fileName);
    $fileName = $pathInfo['filename'] . '_' . $counter . '.' . $pathInfo['extension'];
    $filePath = $uploadDir . $fileName;
    $counter++;
}

if (move_uploaded_file($fileTmpPath, $filePath)) {
    chmod($filePath, 0644);
    echo json_encode(['success' => true, 'message' => 'File uploaded successfully', 'filename' => $fileName, 'size' => $fileSize]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save file']);
}
?>
