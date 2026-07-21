<?php
require_once __DIR__ . '/../src/db.php';

if (!isset($_POST['id'])) {
    die('Missing ID.');
}

$id = (int) $_POST['id'];
$newName = trim($_POST['new_name']);

// Fetch existing file
$stmt = $pdo->prepare("SELECT * FROM files WHERE id = ?");
$stmt->execute([$id]);
$file = $stmt->fetch();

if (!$file) {
    die('File not found.');
}

$uploadDir = __DIR__ . '/../uploads/';
$currentPath = $uploadDir . $file['filename'];
$updatedFilename = $file['filename']; // default: unchanged
$updatedMime = $file['mime_type'];
$updatedSize = $file['size'];

// If user uploaded a replacement file
if (!empty($_FILES['new_file']['name'])) {

    $newFile = $_FILES['new_file'];
    $tmpPath = $newFile['tmp_name'];
    $mimeType = $newFile['type'];
    $size = $newFile['size'];

    // Generate new stored filename
    $newStoredName = uniqid() . '_' . basename($newFile['name']);
    $newPath = $uploadDir . $newStoredName;

    // Move new file
    if (!move_uploaded_file($tmpPath, $newPath)) {
        die('Failed to replace file.');
    }

    // Delete old file
    if (file_exists($currentPath)) {
        unlink($currentPath);
    }

    // Update metadata
    $updatedFilename = $newStoredName;
    $updatedMime = $mimeType;
    $updatedSize = $size;
}

// Update database record
$stmt = $pdo->prepare("
    UPDATE files
    SET original_name = ?, filename = ?, mime_type = ?, size = ?, updated_at = NOW()
    WHERE id = ?
");
$stmt->execute([$newName, $updatedFilename, $updatedMime, $updatedSize, $id]);

echo "File updated.";
echo "<br><a href='list.php'>Back</a>";
