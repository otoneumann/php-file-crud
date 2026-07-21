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

$originalName = trim($_POST['original_name'] ?? '');
if ($originalName === '') {
    exit('Name cannot be empty.');
}

$stmt = $pdo->prepare('UPDATE files SET original_name = ? WHERE id = ?');
$stmt->execute([$originalName, $id]);

header('Location: list.php');
exit;
