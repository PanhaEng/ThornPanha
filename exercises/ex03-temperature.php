<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$pageTitle = 'លំហាត់ ០៣ — បំប្លែងសីតុណ្ហភាព';
$result    = null;
$formData  = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = [
        'value' => $_POST['value'] ?? '',
        'from'  => $_POST['from']  ?? 'C',
        'to'    => $_POST['to']    ?? 'F',
    ];
    if ($formData['value'] !== '') {
        $converted = convertTemperature((float)$formData['value'], $formData['from'], $formData['to']);
        // Convert to all 3 units
        $celsius = convertTemperature((float)$formData['value'], $formData['from'], 'C');
        $result  = [
            'input'       => $formData['value'],
            'fromLabel'   => temperatureLabel($formData['from']),
            'toLabel'     => temperatureLabel($formData['to']),
            'converted'   => round($converted, 4),
            'celsius'     => round($celsius, 2),
            'fahrenheit'  => round(convertTemperature($celsius, 'C', 'F'), 2),
            'kelvin'      => round(convertTemperature($celsius, 'C', 'K'), 2),
        ];
    }
}

include '../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header bg-danger text-white">
                <i class="bi bi-thermometer-half me-2"></i>
                <strong>EX03 — បំប្លែងសីតុណ្ហភាព</strong>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label"><i class="bi bi-thermometer me-1 text-danger"></i>សីតុណ្ហភាព <span class="text-danger">*</span></label>
                            <input type="number" step="any" name="value" class="form-control"
                                   placeholder="ឧ. 100"
                                   value="<?= htmlspecialchars($formData['value'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="bi bi-arrow-right-circle me-1 text-warning"></i>ពី</label>
                            <select name="from" class="form-select">
                                <?php foreach (['C','F','K'] as $u): ?>
                                    <option value="<?= $u ?>" <?= (($formData['from'] ?? 'C') === $u) ? 'selected' : '' ?>>
                                        <?= temperatureLabel($u) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="bi bi-arrow-right-circle-fill me-1 text-success"></i>ទៅ</label>
                            <select name="to" class="form-select">
                                <?php foreach (['C','F','K'] as $u): ?>
                                    <option value="<?= $u ?>" <?= (($formData['to'] ?? 'F') === $u) ? 'selected' : '' ?>>
                                        <?= temperatureLabel($u) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-danger px-4">
                            <i class="bi bi-arrow-left-right me-2"></i>បំប្លែង
                        </button>
                        <a href="ex03-temperature.php" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>សើស
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <?php if ($result): ?>
        <div class="result-box success fade-in">
            <h5 class="fw-bold text-danger mb-3"><i class="bi bi-thermometer-sun me-2"></i>លទ្ធផល</h5>
            <div class="text-center py-2 mb-3">
                <span class="fs-5 text-muted"><?= htmlspecialchars($result['input']) ?> <?= $result['fromLabel'] ?></span>
                <i class="bi bi-arrow-right mx-3 text-primary fs-4"></i>
                <span class="result-value text-danger"><?= $result['converted'] ?>°</span>
                <div class="text-muted small"><?= $result['toLabel'] ?></div>
            </div>
            <hr>
            <h6 class="fw-bold mb-2 text-muted">ទូទៅ (All Units)</h6>
            <div class="row g-2 text-center">
                <div class="col-4">
                    <div class="p-3 bg-white rounded-3 shadow-sm">
                        <div class="text-muted small mb-1">Celsius</div>
                        <div class="fs-4 fw-bold text-danger"><?= $result['celsius'] ?>°C</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="p-3 bg-white rounded-3 shadow-sm">
                        <div class="text-muted small mb-1">Fahrenheit</div>
                        <div class="fs-4 fw-bold text-warning"><?= $result['fahrenheit'] ?>°F</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="p-3 bg-white rounded-3 shadow-sm">
                        <div class="text-muted small mb-1">Kelvin</div>
                        <div class="fs-4 fw-bold text-info"><?= $result['kelvin'] ?> K</div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Reference Card -->
        <div class="card mt-4 border-0 bg-light">
            <div class="card-body small">
                <h6 class="fw-bold mb-2"><i class="bi bi-info-circle me-1 text-danger"></i>រូបមន្ត</h6>
                <ul class="mb-0">
                    <li>°C → °F : <code>(°C × 9/5) + 32</code></li>
                    <li>°F → °C : <code>(°F − 32) × 5/9</code></li>
                    <li>°C → K  : <code>°C + 273.15</code></li>
                    <li>K  → °C : <code>K − 273.15</code></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
