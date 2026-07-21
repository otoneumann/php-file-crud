<?php
require_once __DIR__ . '/../src/db.php';

$stmt = $pdo->query("SELECT * FROM files ORDER BY created_at DESC");
$files = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<body>

<h2>Uploaded Files</h2>

<table border="1" cellpadding="6">
    <tr>
        <th>ID</th>
        <th>Original Name</th>
        <th>MIME Type</th>
        <th>Size (bytes)</th>
        <th>Created</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($files as $file): ?>
        <tr>
            <td><?= $file['id'] ?></td>
            <td><?= htmlspecialchars($file['original_name']) ?></td>
            <td><?= $file['mime_type'] ?></td>
            <td><?= $file['size'] ?></td>
            <td><?= $file['created_at'] ?></td>
            <td>
                <a href="download.php?id=<?= $file['id'] ?>">Download</a> |
                <a href="edit.php?id=<?= $file['id'] ?>">Edit</a> |
                <a href="delete.php?id=<?= $file['id'] ?>">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<br>
<a href="index.php">Upload new file</a>

</body>
</html>
