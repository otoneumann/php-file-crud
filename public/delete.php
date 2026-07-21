<?php
require_once __DIR__ . '/../src/db.php';

if (!isset($_GET['id'])) {
    die('Missing file ID.');
}

$id = (int) $_GET['id'];

// Fetch file
$stmt = $pdo->prepare("SELECT * FROM files WHERE id = ?");
$stmt->execute([$id]);
$file = $stmt->fetch();

if (!$file) {
    die('File not found.');
}

$uploadDir = __DIR__ . '/../uploads/';
$storedName = $file['filename'];
$targetPath = $uploadDir . $storedName;

// Delete file from disk
if (file_exists($targetPath)) {
    unlink($targetPath);
}

// Delete from database
$stmt = $pdo->prepare("DELETE FROM files WHERE id = ?");
$stmt->execute([$id]);

// Redirect back to list
header("Location: list.php");
exit;
