<?php
declare(strict_types=1);
require __DIR__ . '/header.php';
?>

<div class="row">
    <div class="col-md-6 offset-md-3">

        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Create New CSV File</h5>
            </div>

            <div class="card-body">

                <form action="create_csv_save.php" method="post">
                    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">

                    <div class="mb-3">
                        <label class="form-label">CSV Filename (without extension)</label>
                        <input type="text" name="filename" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-success">Create CSV</button>
                    <a href="list.php" class="btn btn-secondary ms-2">Cancel</a>
                </form>

            </div>
        </div>

    </div>
</div>

<?php require __DIR__ . '/footer.php'; ?>
