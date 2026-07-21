<?php
require_once __DIR__ . '/../src/db.php';

if (!isset($_GET['id'])) {
    die('Missing ID.');
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
<body>

<h2>Edit File</h2>

<form action="update.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $file['id'] ?>">

    <label>Rename file:</label><br>
    <input type="text" name="new_name" value="<?= htmlspecialchars($file['original_name']) ?>"><br><br>

    <label>Replace file (optional):</label><br>
    <input type="file" name="new_file"><br><br>

    <button type="submit">Save changes</button>
</form>

<br>
<a href="list.php">Back</a>

</body>
</html>
