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

if (!file_exists($targetPath)) {
    die('File missing on server.');
}

// Send file to browser
header('Content-Description: File Transfer');
header('Content-Type: ' . $file['mime_type']);
header('Content-Disposition: attachment; filename="' . $file['original_name'] . '"');
header('Content-Length: ' . $file['size']);

readfile($targetPath);
exit;
