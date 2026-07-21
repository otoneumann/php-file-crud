<?php require_once __DIR__ . '/../src/db.php'; ?>
<!DOCTYPE html>
<html>
<body>

<h2>Upload a File</h2>

<form action="upload.php" method="post" enctype="multipart/form-data">
    <input type="file" name="file" required>
    <button type="submit">Upload</button>
</form>

<br>
<a href="list.php">View uploaded files</a>

</body>
</html>
