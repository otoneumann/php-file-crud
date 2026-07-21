<?php
require_once __DIR__ . '/../src/db.php';

// Pagination settings
$perPage = 5;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page < 1) $page = 1;

// Count total files
$countStmt = $pdo->query("SELECT COUNT(*) AS cnt FROM files");
$totalFiles = (int) $countStmt->fetch()['cnt'];
$totalPages = $totalFiles > 0 ? (int) ceil($totalFiles / $perPage) : 1;
$offset = ($page - 1) * $perPage;

// Fetch files for current page
$stmt = $pdo->prepare("SELECT * FROM files ORDER BY id DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$files = $stmt->fetchAll();
?>

<?php include 'header.php'; ?>

<h2 class="mb-4">Uploaded Files</h2>

<?php if (empty($files)): ?>
    <div class="alert alert-info">No files uploaded yet.</div>
<?php else: ?>
    <table class="table table-bordered table-striped shadow-sm align-middle">
        <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Preview / Name</th>
            <th>MIME</th>
            <th>Size</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>

        <?php foreach ($files as $file): ?>
            <?php
            $isImage = str_starts_with($file['mime_type'], 'image/');
            $filePath = '../uploads/' . $file['filename'];
            ?>
            <tr>
                <td><?= $file['id'] ?></td>

                <td>
                    <?php if ($isImage): ?>
                        <img src="<?= $filePath ?>"
                             alt="preview"
                             style="width:60px;height:auto;border-radius:4px;margin-right:10px;">
                    <?php endif; ?>

                    <?= htmlspecialchars($file['original_name']) ?>
                </td>

                <td><?= htmlspecialchars($file['mime_type']) ?></td>
                <td><?= $file['size'] ?></td>

                <td>
                    <a href="download.php?id=<?= $file['id'] ?>" class="btn btn-sm btn-success">Download</a>
                    <a href="edit.php?id=<?= $file['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="delete.php?id=<?= $file['id'] ?>" class="btn btn-sm btn-danger">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>
<?php endif; ?>

<!-- Pagination -->
<?php if ($totalFiles > 0): ?>
    <nav class="mt-4">
        <ul class="pagination">

            <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="list.php?page=<?= $page - 1 ?>">Prev</a>
                </li>
            <?php endif; ?>

            <li class="page-item active">
                <span class="page-link"><?= $page ?> / <?= $totalPages ?></span>
            </li>

            <?php if ($page < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="list.php?page=<?= $page + 1 ?>">Next</a>
                </li>
            <?php endif; ?>

        </ul>
    </nav>
<?php endif; ?>

<?php include 'footer.php'; ?>
