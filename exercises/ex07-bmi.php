<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$pageTitle = 'លំហាត់ ០៧ — BMI Calculator';
$result    = null;
$formData  = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = [
        'weight' => $_POST['weight'] ?? '',
        'height' => $_POST['height'] ?? '',
        'name'   => sanitize($_POST['name'] ?? ''),
    ];
    if ($formData['weight'] > 0 && $formData['height'] > 0) {
        $result = calculateBMI((float)$formData['weight'], (float)$formData['height']);
        $result['weight'] = $formData['weight'];
        $result['height'] = $formData['height'];
        $result['name']   = $formData['name'];
    }
}

// BMI scale segments
$bmiSegments = [
    ['label'=>'Underweight','range'=>'< 18.5','color'=>'#0dcaf0','width'=>15],
    ['label'=>'Normal',     'range'=>'18.5–25','color'=>'#198754','width'=>25],
    ['label'=>'Overweight', 'range'=>'25–30',  'color'=>'#ffc107','width'=>20],
    ['label'=>'Obese',      'range'=>'≥ 30',   'color'=>'#dc3545','width'=>40],
];

include '../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="row g-4">
            <!-- Form -->
            <div class="col-md-5">
                <div class="card shadow h-100">
                    <div class="card-header bg-success text-white">
                        <i class="bi bi-activity me-2"></i><strong>EX07 — BMI</strong>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-person me-1"></i>ឈ្មោះ</label>
                                <input type="text" name="name" class="form-control"
                                       placeholder="(ស្រេចចិត្ត)"
                                       value="<?= htmlspecialchars($formData['name'] ?? '') ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-graph-up me-1 text-warning"></i>
                                    ទម្ងន់ (kg) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" step="0.1" name="weight" class="form-control"
                                           placeholder="ឧ. 65" min="1" max="300"
                                           value="<?= htmlspecialchars($formData['weight'] ?? '') ?>" required>
                                    <span class="input-group-text">kg</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-arrows-vertical me-1 text-info"></i>
                                    កំពស់ (cm) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" step="0.1" name="height" class="form-control"
                                           placeholder="ឧ. 170" min="50" max="250"
                                           value="<?= htmlspecialchars($formData['height'] ?? '') ?>" required>
                                    <span class="input-group-text">cm</span>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-calculator me-2"></i>គណនា BMI
                            </button>
                        </form>

                        <!-- BMI Scale Legend -->
                        <div class="mt-4">
                            <h6 class="fw-bold text-muted small mb-2">ចំណាត់ថ្នាក់ BMI</h6>
                            <?php foreach ($bmiSegments as $seg): ?>
                            <div class="d-flex align-items-center mb-1 small">
                                <span class="rounded me-2" style="width:16px;height:16px;background:<?= $seg['color'] ?>;display:inline-block;"></span>
                                <span><?= $seg['label'] ?> <span class="text-muted">(<?= $seg['range'] ?>)</span></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Result -->
            <div class="col-md-7">
                <?php if ($result): ?>
                <div class="card shadow fade-in">
                    <div class="card-header bg-<?= $result['color'] ?> <?= in_array($result['color'],['warning','info']) ? 'text-dark' : 'text-white' ?>">
                        <i class="bi bi-clipboard2-data me-2"></i><strong>លទ្ធផល BMI</strong>
                    </div>
                    <div class="card-body p-4">
                        <?php if (!empty($result['name'])): ?>
                        <p class="text-muted mb-3">
                            <i class="bi bi-person-fill me-1"></i><?= htmlspecialchars($result['name']) ?>
                        </p>
                        <?php endif; ?>

                        <!-- Big BMI Number -->
                        <div class="text-center py-3">
                            <div class="text-muted small mb-1">BMI របស់អ្នក</div>
                            <div class="result-value text-<?= $result['color'] ?>"><?= $result['bmi'] ?></div>
                            <div class="fs-5 fw-bold mt-1 text-<?= $result['color'] ?>"><?= $result['category'] ?></div>
                        </div>

                        <!-- BMI Scale Bar -->
                        <div class="mt-3 mb-2">
                            <div class="d-flex rounded overflow-hidden" style="height:18px;">
                                <?php foreach ($bmiSegments as $seg): ?>
                                <div style="flex:<?= $seg['width'] ?>;background:<?= $seg['color'] ?>;"></div>
                                <?php endforeach; ?>
                            </div>
                            <div class="d-flex justify-content-between text-muted" style="font-size:.7rem;">
                                <span>10</span><span>18.5</span><span>25</span><span>30</span><span>40+</span>
                            </div>
                        </div>

                        <!-- Details Table -->
                        <table class="table table-sm table-bordered mt-3 mb-0">
                            <tr><th class="bg-success text-white">ទម្ងន់</th><td><?= $result['weight'] ?> kg</td></tr>
                            <tr><th class="bg-success text-white">កំពស់</th><td><?= $result['height'] ?> cm</td></tr>
                            <tr><th class="bg-success text-white">BMI</th><td class="fw-bold fs-5"><?= $result['bmi'] ?></td></tr>
                            <tr><th class="bg-success text-white">ចំណាត់ថ្នាក់</th>
                                <td><span class="badge bg-<?= $result['color'] ?>"><?= $result['category'] ?></span></td></tr>
                            <tr><th class="bg-success text-white">ទម្ងន់ល្អ</th>
                                <td><?= $result['idealWeightMin'] ?> – <?= $result['idealWeightMax'] ?> kg</td></tr>
                        </table>
                    </div>
                </div>
                <?php else: ?>
                <div class="card border-0 bg-light h-100 d-flex align-items-center justify-content-center">
                    <div class="text-center text-muted p-5">
                        <i class="bi bi-activity fs-1 d-block mb-2 text-success opacity-50"></i>
                        <p>បំពេញ ទម្ងន់ &amp; កំពស់ ហើយចុចគណនា</p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
