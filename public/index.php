<?php require_once __DIR__ . '/../src/db.php'; ?>
<?php include 'header.php'; ?>

<h2 class="mb-4">Upload a File</h2>

<form action="upload.php" method="post" enctype="multipart/form-data" class="card p-4 shadow-sm">
    <div class="mb-3">
        <input type="file" name="file" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Upload</button>
</form>

<?php include 'footer.php'; ?>
