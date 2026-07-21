<?php
require_once __DIR__ . '/../src/db.php';

if (!isset($_POST['id'])) {
    die('Missing file ID.');
}

$id = (int) $_POST['id'];
$newName = $_POST['original_name'];

// Fetch existing file
$stmt = $pdo->prepare("SELECT * FROM files WHERE id = ?");
$stmt->execute([$id]);
$file = $stmt->fetch();

if (!$file) {
    die('File not found.');
}

$uploadDir = __DIR__ . '/../uploads/';
$storedName = $file['filename'];
$targetPath = $uploadDir . $storedName;

// If a new file is uploaded → replace it
if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {

    $tmpPath = $_FILES['file']['tmp_name'];
    $mimeType = $_FILES['file']['type'];
    $size = $_FILES['file']['size'];

    // Replace file on disk
    if (!move_uploaded_file($tmpPath, $targetPath)) {
        die('Failed to replace file.');
    }

    // Update DB with new metadata
    $stmt = $pdo->prepare("
        UPDATE files
        SET original_name = ?, mime_type = ?, size = ?, updated_at = NOW()
        WHERE id = ?
    ");
    $stmt->execute([$newName, $mimeType, $size, $id]);

} else {
    // Only rename original_name
    $stmt = $pdo->prepare("
        UPDATE files
        SET original_name = ?, updated_at = NOW()
        WHERE id = ?
    ");
    $stmt->execute([$newName, $id]);
}

// Redirect back to list
header("Location: list.php");
exit;
