<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$pageTitle = 'លំហាត់ ០៥ — ពិនិត្យពាក្យសម្ងាត់';
$result    = null;
$formData  = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = ['password' => $_POST['password'] ?? ''];
    if (!empty($formData['password'])) {
        $result = checkPasswordStrength($formData['password']);
    }
}

include '../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header bg-info text-dark">
                <i class="bi bi-shield-lock-fill me-2"></i>
                <strong>EX05 — ត្រួតពិនិត្យភាពខ្លាំងពាក្យសម្ងាត់</strong>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="">
                    <label class="form-label fw-bold">
                        <i class="bi bi-key me-1 text-info"></i>ពាក្យសម្ងាត់ <span class="text-danger">*</span>
                    </label>
                    <div class="input-group mb-3">
                        <input type="password" id="live_password" name="password" class="form-control"
                               placeholder="បញ្ចូលពាក្យសម្ងាត់..."
                               value="" autocomplete="new-password" required>
                        <button type="button" class="btn btn-outline-secondary" data-toggle-password="live_password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>

                    <!-- Live Strength Bar -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="text-muted">កម្រិតភាពខ្លាំង</small>
                            <small id="strength_label" class="fw-bold"></small>
                        </div>
                        <div class="strength-bar">
                            <div class="strength-fill" id="strength_fill" style="width:0%"></div>
                        </div>
                    </div>

                    <!-- Live Checklist -->
                    <div class="p-3 bg-light rounded-3 mb-3">
                        <div class="row g-2 small">
                            <div class="col-6" id="chk_length">
                                <i class="bi bi-circle me-1"></i>យ៉ាងហោចណាស់ ៨ តួ
                            </div>
                            <div class="col-6" id="chk_uppercase">
                                <i class="bi bi-circle me-1"></i>អក្សរធំ (A-Z)
                            </div>
                            <div class="col-6" id="chk_lowercase">
                                <i class="bi bi-circle me-1"></i>អក្សរតូច (a-z)
                            </div>
                            <div class="col-6" id="chk_number">
                                <i class="bi bi-circle me-1"></i>ជាតួលេខ (0-9)
                            </div>
                            <div class="col-6" id="chk_special">
                                <i class="bi bi-circle me-1"></i>និមិត្តសញ្ញា (!@#$)
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-info px-4">
                            <i class="bi bi-shield-check me-2"></i>ត្រួតពិនិត្យ
                        </button>
                        <a href="ex05-password.php" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>សើស
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Result -->
        <?php if ($result): ?>
        <div class="card shadow fade-in">
            <div class="card-header bg-<?= $result['color'] ?> <?= in_array($result['color'],['warning','info']) ? 'text-dark' : 'text-white' ?>">
                <i class="bi bi-shield me-2"></i><strong>លទ្ធផលការពិនិត្យ</strong>
            </div>
            <div class="card-body p-4">
                <!-- Score Circle -->
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-<?= $result['color'] ?> text-white"
                         style="width:100px;height:100px;font-size:2.5rem;font-weight:700;">
                        <?= $result['score'] ?>/5
                    </div>
                    <div class="mt-2 fs-4 fw-bold text-<?= $result['color'] ?>"><?= $result['strength'] ?></div>
                </div>

                <!-- Criteria -->
                <h6 class="fw-bold text-muted mb-3">លម្អិត</h6>
                <div class="list-group list-group-flush">
                    <?php
                    $criteriaLabels = [
                        'length'    => ['≥ ៨ តួ',          'bi-rulers'],
                        'uppercase' => ['អក្សរធំ (A-Z)',   'bi-type-h1'],
                        'lowercase' => ['អក្សរតូច (a-z)', 'bi-type'],
                        'number'    => ['ជាតួលេខ (0-9)',   'bi-123'],
                        'special'   => ['និមិត្តសញ្ញា',    'bi-asterisk'],
                    ];
                    foreach ($result['criteria'] as $key => $passed):
                    ?>
                    <div class="list-group-item d-flex align-items-center px-0 py-2">
                        <i class="bi <?= $criteriaLabels[$key][1] ?> me-2 text-muted"></i>
                        <span><?= $criteriaLabels[$key][0] ?></span>
                        <span class="ms-auto badge bg-<?= $passed ? 'success' : 'danger' ?>">
                            <?= $passed ? '✓ បាន' : '✗ គ្មាន' ?>
                        </span>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Strength Bar -->
                <div class="mt-3">
                    <div class="strength-bar" style="height:12px;">
                        <div class="strength-fill bg-<?= $result['color'] ?>"
                             style="width:<?= ($result['score']/5)*100 ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
