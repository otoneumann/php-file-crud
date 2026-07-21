<?php
declare(strict_types=1);
require __DIR__ . '/header.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) exit('Invalid ID.');

$stmt = $pdo->prepare('SELECT filename, original_name, mime_type FROM files WHERE id = ?');
$stmt->execute([$id]);
$file = $stmt->fetch();

if (!$file) exit('File not found.');

$path = __DIR__ . '/../uploads/' . $file['filename'];

if (!is_file($path)) exit('File missing.');

$mime = $file['mime_type'];

// Only allow editing text-based files
$editableMime = [
    'text/plain',
    'text/csv',
    'application/json',
    'application/xml',
    'text/html',
    'text/css',
    'text/javascript'
];

if (!in_array($mime, $editableMime, true)) {
    exit('This file type cannot be edited.');
}

$content = file_get_contents($path);
?>

<div class="row">
    <div class="col-md-8 offset-md-2">

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Edit File Content: <?= e($file['original_name']) ?></h5>
            </div>

            <div class="card-body">

                <form action="update_content.php" method="post">
                    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
                    <input type="hidden" name="id" value="<?= $id ?>">

                    <div class="mb-3">
                        <label class="form-label">Content</label>
                        <textarea name="content" class="form-control" rows="20"><?= e($content) ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-success">Save Changes</button>
                    <a href="list.php" class="btn btn-secondary ms-2">Cancel</a>
                </form>

            </div>
        </div>

    </div>
</div>

<?php require __DIR__ . '/footer.php'; ?>
