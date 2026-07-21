<?php
declare(strict_types=1);
require __DIR__ . '/header.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    exit('Invalid ID.');
}

$stmt = $pdo->prepare('SELECT id, original_name FROM files WHERE id = ?');
$stmt->execute([$id]);
$file = $stmt->fetch();

if (!$file) {
    exit('File not found.');
}
?>

<div class="row">
    <div class="col-md-6 offset-md-3">

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Edit File Name</h5>
            </div>

            <div class="card-body">

                <form action="update.php" method="post">
                    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
                    <input type="hidden" name="id" value="<?= (int)$file['id'] ?>">

                    <div class="mb-3">
                        <label class="form-label">Original Name</label>
                        <input type="text"
                               name="original_name"
                               class="form-control"
                               value="<?= e($file['original_name']) ?>"
                               required>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-success">
                            Save Changes
                        </button>

                        <a href="list.php" class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>

<?php require __DIR__ . '/footer.php'; ?>
