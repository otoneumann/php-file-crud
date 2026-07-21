<?php
declare(strict_types=1);
require __DIR__ . '/header.php';

// A working weather web application using Open-Meteo API (Requires NO API key)
$url = "https://api.open-meteo.com/v1/forecast?latitude=52.52&longitude=13.42&current=temperature_2m,weather_code";

$response = file_get_contents($url);
$data = json_decode($response, true);

$temp = $data['current']['temperature_2m'] ?? 'N/A';
?>
<div class="row">
    <div class="col-md-6 offset-md-3">
        <p>Current Temperature in Berlin: <strong><?php echo $temp; ?>°C</strong></p>
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
