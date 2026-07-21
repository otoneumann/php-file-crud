<?php
declare(strict_types=1);
require __DIR__ . '/header.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Invalid request.');
}

checkCsrf();

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) {
    exit('Invalid ID.');
}

$stmt = $pdo->prepare('SELECT filename FROM files WHERE id = ?');
$stmt->execute([$id]);
$file = $stmt->fetch();

if (!$file) {
    exit('File not found.');
}

$path = __DIR__ . '/../uploads/' . $file['filename'];
if (is_file($path)) {
    unlink($path);
}

$stmt = $pdo->prepare('DELETE FROM files WHERE id = ?');
$stmt->execute([$id]);

header('Location: list.php');
exit;
