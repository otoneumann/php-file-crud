<?php
require_once __DIR__ . '/../src/db.php';

if (!isset($_FILES['file'])) {
    die('No file uploaded.');
}

$file = $_FILES['file'];

if ($file['error'] !== UPLOAD_ERR_OK) {
    die('Upload error.');
}

$originalName = $file['name'];
$mimeType = $file['type'];
$size = $file['size'];

$uploadDir = __DIR__ . '/../uploads/';

// Generate a unique stored filename
$storedName = uniqid() . '_' . basename($originalName);
$targetPath = $uploadDir . $storedName;

// Move file to uploads folder
if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
    die('Failed to save file.');
}

// Insert into database
$stmt = $pdo->prepare("
    INSERT INTO files (filename, original_name, mime_type, size)
    VALUES (?, ?, ?, ?)
");
$stmt->execute([$storedName, $originalName, $mimeType, $size]);

// Redirect back to list
header("Location: list.php");
exit;
