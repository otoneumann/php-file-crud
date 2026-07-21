<?php
require_once __DIR__ . '/../src/db.php';

if (!isset($_GET['id'])) {
    die('Missing file ID.');
}

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM files WHERE id = ?");
$stmt->execute([$id]);
$file = $stmt->fetch();

if (!$file) {
    die('File not found.');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit File</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="mb-4">Edit File</h2>

    <div class="card p-4 shadow-sm">
        <form action="update.php" method="post" enctype="multipart/form-data">

            <input type="hidden" name="id" value="<?= $file['id'] ?>">

            <div class="mb-3">
                <label class="form-label">Original Name</label>
                <input type="text" name="original_name" class="form-control"
                       value="<?= htmlspecialchars($file['original_name']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Replace File (optional)</label>
                <input type="file" name="file" class="form-control">
            </div>

            <button type="submit" class="btn btn-warning">Update</button>
            <a href="list.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>

</body>
</html>
