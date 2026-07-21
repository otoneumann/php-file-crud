<?php
declare(strict_types=1);

// DEBUG: start output buffer
ob_start();

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require __DIR__ . '/../src/db.php';

// CSRF check
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Invalid request.');
}

if (
    empty($_POST['csrf_token']) ||
    empty($_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    exit('Invalid CSRF token.');
}

$filename = trim($_POST['filename'] ?? '');
$filename = preg_replace('/[^a-zA-Z0-9_\-]/', '', $filename);

$internalName = bin2hex(random_bytes(16)) . '.csv';
$uploadDir    = __DIR__ . '/../uploads/';
$path         = $uploadDir . $internalName;

file_put_contents($path, "id,name,email\n");

$stmt = $pdo->prepare(
    'INSERT INTO files (filename, original_name, mime_type, size) VALUES (?, ?, ?, ?)'
);
$stmt->execute([
    $internalName,
    $filename . '.csv',
    'text/csv',
    filesize($path)
]);

$newId = (int)$pdo->lastInsertId();

// DEBUG: capture ANY output
$debug = ob_get_clean();

if ($debug !== '') {
    echo "<pre>DEBUG OUTPUT BEFORE REDIRECT:\n";
    echo htmlspecialchars($debug);
    echo "\n</pre>";
    exit("STOP: Something printed output BEFORE redirect.");
}

header('Location: edit_content.php?id=' . $newId);
exit;
