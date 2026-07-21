<?php
require_once __DIR__ . '/../src/db.php';

if (!isset($_GET['id'])) {
    die('Missing ID.');
}

$id = (int) $_GET['id'];

// Fetch file info
$stmt = $pdo->prepare("SELECT * FROM files WHERE id = ?");
$stmt->execute([$id]);
$file = $stmt->fetch();

if (!$file) {
    die('File not found.');
}

$path = __DIR__ . '/../uploads/' . $file['filename'];

// Delete file from disk
if (file_exists($path)) {
    unlink($path);
}

// Delete from database
$stmt = $pdo->prepare("DELETE FROM files WHERE id = ?");
$stmt->execute([$id]);

echo "File deleted.";
echo "<br><a href='list.php'>Back</a>";
