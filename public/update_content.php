<?php
declare(strict_types=1);
require __DIR__ . '/header.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') exit('Invalid request.');
checkCsrf();

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) exit('Invalid ID.');

$content = $_POST['content'] ?? '';

$stmt = $pdo->prepare('SELECT filename, mime_type FROM files WHERE id = ?');
$stmt->execute([$id]);
$file = $stmt->fetch();

if (!$file) exit('File not found.');

$path = __DIR__ . '/../uploads/' . $file['filename'];

$editableMime = [
    'text/plain',
    'text/csv',
    'application/json',
    'application/xml',
    'text/html',
    'text/css',
    'text/javascript'
];

if (!in_array($file['mime_type'], $editableMime, true)) {
    exit('This file type cannot be edited.');
}

file_put_contents($path, $content);

header('Location: list.php');
exit;
