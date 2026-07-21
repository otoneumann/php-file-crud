<?php
require_once __DIR__ . '/../src/db.php';

if (!isset($_GET['id'])) {
    die('Missing file ID.');
}

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM files WHERE id = ?");
$stmt->execute([$id]);
$file = $stmt->fetch();

if (!$file) {
    die('File not found.');
}

$uploadDir = __DIR__ . '/../uploads/';
$storedName = $file['filename'];
$path = $uploadDir . $storedName;

if (!file_exists($path)) {
    die('File missing on server.');
}

$mime = $file['mime_type'];

// If browser can display it → show inline
$inlineTypes = [
    'image/',
    'text/',
    'audio/',
    'video/',
    'application/pdf'
];

$canInline = false;
foreach ($inlineTypes as $type) {
    if (str_starts_with($mime, $type)) {
        $canInline = true;
        break;
    }
}

if ($canInline) {
    header("Content-Type: $mime");
    header("Content-Disposition: inline; filename=\"" . $file['original_name'] . "\"");
    readfile($path);
    exit;
}

// Otherwise → download
header("Content-Type: $mime");
header("Content-Disposition: attachment; filename=\"" . $file['original_name'] . "\"");
readfile($path);
exit;
