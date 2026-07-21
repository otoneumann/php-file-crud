<?php
require_once __DIR__ . '/../src/db.php';

if (!isset($_GET['id'])) {
    die('Missing ID.');
}

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM files WHERE id = ?");
$stmt->execute([$id]);
$file = $stmt->fetch();

if (!$file) {
    die('File not found.');
}

$path = __DIR__ . '/../uploads/' . $file['filename'];

if (!file_exists($path)) {
    die('File missing on disk.');
}

header('Content-Description: File Transfer');
header('Content-Type: ' . $file['mime_type']);
header('Content-Disposition: attachment; filename="' . $file['original_name'] . '"');
header('Content-Length: ' . $file['size']);

readfile($path);
exit;
