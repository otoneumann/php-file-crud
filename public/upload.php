<?php
declare(strict_types=1);
require __DIR__ . '/header.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Invalid request.');
}

checkCsrf();

if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    exit('Upload failed.');
}

$maxSize = 10 * 1024 * 1024; // 10 MB
if ($_FILES['file']['size'] > $maxSize) {
    exit('File too large.');
}

$allowedExtensions = [
    'jpg','jpeg','png','gif','webp',
    'pdf',
    'txt','csv','log','json',
    'mp3','wav',
    'mp4','webm',
];

$allowedMime = [
    'image/jpeg','image/png','image/gif','image/webp',
    'application/pdf',
    'text/plain','text/csv','application/json',
    'audio/mpeg','audio/wav',
    'video/mp4','video/webm',
];

$originalName = trim($_FILES['file']['name'] ?? '');
$extension    = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

if ($originalName === '' || !in_array($extension, $allowedExtensions, true)) {
    exit('File type not allowed.');
}

$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime  = $finfo->file($_FILES['file']['tmp_name']);

if ($mime === false || !in_array($mime, $allowedMime, true)) {
    exit('MIME type not allowed.');
}

$internalName = bin2hex(random_bytes(16)) . '.' . $extension;
$uploadDir    = __DIR__ . '/../uploads/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0700, true);
}

$targetPath = $uploadDir . $internalName;

if (!move_uploaded_file($_FILES['file']['tmp_name'], $targetPath)) {
    exit('Could not move uploaded file.');
}

$stmt = $pdo->prepare(
    'INSERT INTO files (filename, original_name, mime_type, size) VALUES (?, ?, ?, ?)'
);
$stmt->execute([
    $internalName,
    $originalName,
    $mime,
    $_FILES['file']['size'],
]);

header('Location: list.php');
exit;
