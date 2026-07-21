<?php
declare(strict_types=1);
require __DIR__ . '/header.php';
?>
<div class="row">
    <div class="col-md-6 offset-md-3">
        <h2 class="mb-3">Upload file</h2>

        <form action="upload.php" method="post" enctype="multipart/form-data" class="card card-body">
            <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">

            <div class="mb-3">
                <label class="form-label">Select file</label>
                <input type="file" name="file" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
    </div>
</div>
<?php require __DIR__ . '/footer.php'; ?>
