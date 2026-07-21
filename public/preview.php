<?php
declare(strict_types=1);
require __DIR__ . '/header.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    exit('Invalid ID.');
}

$stmt = $pdo->prepare('SELECT filename, original_name, mime_type FROM files WHERE id = ?');
$stmt->execute([$id]);
$file = $stmt->fetch();

if (!$file) {
    http_response_code(404);
    exit('File not found.');
}

$path = __DIR__ . '/../uploads/' . $file['filename'];
if (!is_file($path)) {
    http_response_code(404);
    exit('File missing.');
}

$safeInlineMime = [
    'image/jpeg','image/png','image/gif','image/webp',
    'application/pdf',
    'text/plain','text/csv','application/json',
    'audio/mpeg','audio/wav',
    'video/mp4','video/webm',
];

$mime = $file['mime_type'];

header('Content-Type: ' . $mime);
header('Content-Length: ' . filesize($path));

$disposition = in_array($mime, $safeInlineMime, true) ? 'inline' : 'attachment';
header('Content-Disposition: ' . $disposition . '; filename="' . basename($file['original_name']) . '"');

readfile($path);
