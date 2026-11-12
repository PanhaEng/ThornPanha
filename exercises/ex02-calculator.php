<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$pageTitle = 'លំហាត់ ០២ — ម៉ាស៊ីនគិតលេខ';
$result    = null;
$formData  = [];

if (($_SERVER["REQUEST_METHOD"] ?? '') === "POST" && isset($_POST['btn_calc'])) {
    $formData = [
        'num1' => $_POST['num1'] ?? '',
        'num2' => $_POST['num2'] ?? '',
        'op'   => $_POST['op']   ?? '+',
    ];
    if ($formData['num1'] !== '' && $formData['num2'] !== '') {
        $result = calculate((float)$formData['num1'], (float)$formData['num2'], $formData['op']);
        $result['num1'] = $formData['num1'];
        $result['num2'] = $formData['num2'];
        $result['op']   = $formData['op'];
    }
}

$opLabels = ['+'=>'បូក (+)', '-'=>'ដក (−)', '*'=>'គុណ (×)', '/'=>'ចែក (÷)', '%'=>'Modulus (%)', '**'=>'លំហ (^)'];

include '../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header bg-success text-white">
                <i class="bi bi-calculator me-2"></i>
                <strong>EX02 — ម៉ាស៊ីនគិតលេខ</strong>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label"><i class="bi bi-123 me-1 text-primary"></i>លេខ A <span class="text-danger">*</span></label>
                            <input type="number" step="any" name="num1" class="form-control"
                                   placeholder="ឧ. 15"
                                   value="<?= htmlspecialchars($formData['num1'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="bi bi-plus-slash-minus me-1 text-success"></i>ប្រតិបត្តិករ</label>
                            <select name="op" class="form-select">
                                <?php foreach ($opLabels as $val => $lbl): ?>
                                    <option value="<?= $val ?>" <?= (($formData['op'] ?? '+') === $val) ? 'selected' : '' ?>>
                                        <?= $lbl ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="bi bi-123 me-1 text-danger"></i>លេខ B <span class="text-danger">*</span></label>
                            <input type="number" step="any" name="num2" class="form-control"
                                   placeholder="ឧ. 4"
                                   value="<?= htmlspecialchars($formData['num2'] ?? '') ?>" required>
                        </div>
                    </div>
                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-success px-4">
                            <i class="bi bi-equals me-2"></i>គណនា
                        </button>
                        <a href="ex02-calculator.php" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>សើស
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Result -->
        <?php if ($result !== null): ?>
        <div class="result-box <?= $result['error'] ? 'danger' : 'success' ?> fade-in">
            <?php if ($result['error']): ?>
                <div class="alert alert-danger mb-0">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i><?= $result['error'] ?>
                </div>
            <?php else: ?>
                <h5 class="text-success fw-bold mb-3"><i class="bi bi-check-circle-fill me-2"></i>លទ្ធផល</h5>
                <div class="text-center py-3">
                    <div class="fs-2 fw-bold text-muted mb-1">
                        <?= formatNumber($result['num1'], 0) ?>
                        <span class="text-primary"><?= htmlspecialchars($result['op']) ?></span>
                        <?= formatNumber($result['num2'], 0) ?>
                        <span class="text-secondary">=</span>
                    </div>
                    <div class="result-value text-success">
                        <?= formatNumber($result['result']) ?>
                    </div>
                </div>
                <table class="table table-sm table-bordered mt-2 mb-0">
                    <tr><th class="bg-success text-white w-40">ប្រតិបត្តិករ</th><td><?= $opLabels[$result['op']] ?></td></tr>
                    <tr><th class="bg-success text-white">លេខ A</th><td><?= htmlspecialchars($result['num1']) ?></td></tr>
                    <tr><th class="bg-success text-white">លេខ B</th><td><?= htmlspecialchars($result['num2']) ?></td></tr>
                    <tr><th class="bg-success text-white">លទ្ធផល</th><td class="fw-bold fs-5 text-success"><?= formatNumber($result['result']) ?></td></tr>
                </table>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <div class="card mt-4 border-0 bg-light">
            <div class="card-body small">
                <h6 class="fw-bold"><i class="bi bi-code-slash me-2 text-success"></i>Functions ប្រើ</h6>
                <code>calculate(float $a, float $b, string $op): array</code>
                <p class="mb-0 mt-1 text-muted">ប្រើ switch statement ដំណើរការ ៦ ប្រតិបត្តិករ: + − × ÷ % **</p>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
