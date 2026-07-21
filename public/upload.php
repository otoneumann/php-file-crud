<?php
require_once __DIR__ . '/../src/db.php';

$uploadDir = __DIR__ . '/../uploads/';

if (!isset($_FILES['file'])) {
    die('No file uploaded.');
}

$file = $_FILES['file'];

$originalName = $file['name'];
$tmpPath      = $file['tmp_name'];
$mimeType     = $file['type'];
$size         = $file['size'];

// Generate a safe unique filename for storage
$storedName = uniqid() . '_' . basename($originalName);

$targetPath = $uploadDir . $storedName;

// Move file to uploads folder
if (!move_uploaded_file($tmpPath, $targetPath)) {
    die('Failed to move uploaded file.');
}

// Insert metadata into database
$stmt = $pdo->prepare("
    INSERT INTO files (filename, original_name, mime_type, size)
    VALUES (?, ?, ?, ?)
");
$stmt->execute([$storedName, $originalName, $mimeType, $size]);

echo "File uploaded successfully.";
echo "<br><a href='index.php'>Back</a>";
