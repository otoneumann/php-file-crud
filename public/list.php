<?php
declare(strict_types=1);
require __DIR__ . '/header.php';

$stmt = $pdo->query('SELECT id, original_name, mime_type, size, created_at FROM files ORDER BY id DESC');
$files = $stmt->fetchAll();
?>

<div class="row">
    <div class="col-12">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0">Files</h2>

            <div>
                <a href="create_csv.php" class="btn btn-success me-2">Create CSV</a>
                <a href="index.php" class="btn btn-primary">Upload New File</a>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-0">

                <table class="table table-striped table-hover mb-0 align-middle">
                    <thead class="table-dark">
                    <tr>
                        <th style="width: 60px;">ID</th>
                        <th>Name</th>
                        <th>MIME</th>
                        <th style="width: 120px;">Size</th>
                        <th style="width: 180px;">Created</th>
                        <th style="width: 300px;">Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($files as $file): ?>
                        <tr>
                            <td><?= (int)$file['id'] ?></td>
                            <td><?= e($file['original_name']) ?></td>
                            <td><?= e($file['mime_type']) ?></td>
                            <td><?= number_format((int)$file['size'] / 1024, 2) ?> KB</td>
                            <td><?= e($file['created_at']) ?></td>

                            <td class="text-nowrap">

                                <a href="preview.php?id=<?= (int)$file['id'] ?>"
                                   class="btn btn-sm btn-secondary me-1">
                                    Preview
                                </a>

                                <a href="download.php?id=<?= (int)$file['id'] ?>"
                                   class="btn btn-sm btn-info me-1">
                                    Download
                                </a>

                                <a href="edit.php?id=<?= (int)$file['id'] ?>"
                                   class="btn btn-sm btn-warning me-1">
                                    Edit Name
                                </a>

                                <a href="edit_content.php?id=<?= (int)$file['id'] ?>"
                                   class="btn btn-sm btn-primary me-1">
                                    Edit Content
                                </a>

                                <form action="delete.php" method="post" class="d-inline">
                                    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
                                    <input type="hidden" name="id" value="<?= (int)$file['id'] ?>">

                                    <button type="submit"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Delete this file?');">
                                        Delete
                                    </button>
                                </form>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>

                </table>

            </div>
        </div>

    </div>
</div>

<?php require __DIR__ . '/footer.php'; ?>
