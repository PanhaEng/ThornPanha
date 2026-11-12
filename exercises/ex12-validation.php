<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$pageTitle  = 'លំហាត់ ១២ — Form Validation (Regex)';
$formData   = [];
$errors     = [];
$submitted  = false;
$allValid   = false;

// Individual regex validators
function validateUsername(string $v): array {
    $pattern = '/^[a-zA-Z0-9_]{4,20}$/';
    $ok = (bool) preg_match($pattern, $v);
    return [
        'valid'   => $ok,
        'pattern' => $pattern,
        'message' => $ok ? 'ឈ្មោះអ្នកប្រើត្រឹមត្រូវ!' : 'ត្រូវការ 4-20 តួ: a-z, A-Z, 0-9, _',
    ];
}

function validateEmailAddr(string $v): array {
    $pattern = '/^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$/';
    $ok = (bool) preg_match($pattern, $v);
    return [
        'valid'   => $ok,
        'pattern' => $pattern,
        'message' => $ok ? 'Email ត្រឹមត្រូវ!' : 'ទម្រង់ Email មិនត្រឹមត្រូវ (ឧ. user@example.com)',
    ];
}

function validateKhmerPhone(string $v): array {
    $clean   = preg_replace('/[\s\-]/', '', $v);
    $pattern = '/^(?:\+855|0)(?:1[0-9]|6[0-9]|7[0-9]|8[0-9]|9[0-9])[0-9]{6,7}$/';
    $ok = (bool) preg_match($pattern, $clean);
    return [
        'valid'   => $ok,
        'pattern' => $pattern,
        'message' => $ok ? 'លេខទូរស័ព្ទត្រឹមត្រូវ!' : 'ត្រូវការលេខខ្មែរ (ឧ. 012345678 / +85512345678)',
    ];
}

function validateStrongPwd(string $v): array {
    $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&.#^_\-])[A-Za-z\d@$!%*?&.#^_\-]{8,}$/';
    $ok = (bool) preg_match($pattern, $v);
    $checks = [
        'length'  => strlen($v) >= 8,
        'upper'   => (bool) preg_match('/[A-Z]/', $v),
        'lower'   => (bool) preg_match('/[a-z]/', $v),
        'digit'   => (bool) preg_match('/\d/', $v),
        'special' => (bool) preg_match('/[@$!%*?&.#^_\-]/', $v),
    ];
    return [
        'valid'   => $ok,
        'pattern' => $pattern,
        'checks'  => $checks,
        'message' => $ok ? 'ពាក្យសម្ងាត់រឹងមាំ!' : 'ពាក្យសម្ងាត់មិនបំពេញលក្ខខណ្ឌ',
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submitted = true;
    $formData  = [
        'username' => trim($_POST['username'] ?? ''),
        'email'    => trim($_POST['email']    ?? ''),
        'phone'    => trim($_POST['phone']    ?? ''),
        'password' =>      $_POST['password'] ?? '',
    ];

    $results = [
        'username' => validateUsername($formData['username']),
        'email'    => validateEmailAddr($formData['email']),
        'phone'    => validateKhmerPhone($formData['phone']),
        'password' => validateStrongPwd($formData['password']),
    ];

    $allValid = $results['username']['valid']
             && $results['email']['valid']
             && $results['phone']['valid']
             && $results['password']['valid'];
}

include '../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-8">

        <!-- Alert Banner -->
        <?php if ($submitted): ?>
        <div class="alert alert-<?= $allValid ? 'success' : 'danger' ?> d-flex align-items-center fade-in mb-4">
            <i class="bi bi-<?= $allValid ? 'check-circle-fill' : 'exclamation-triangle-fill' ?> me-3 fs-4"></i>
            <div>
                <strong><?= $allValid ? 'អបអរសាទរ! ទិន្នន័យទាំងអស់ត្រឹមត្រូវ!' : 'ការផ្ទៀងផ្ទាត់បរាជ័យ!' ?></strong><br>
                <small><?= $allValid ? 'Form ឆ្លងកាត់ Regex Validation ទាំងអស់ ✅' : 'សូមពិនិត្យ និងកែតម្រូវប្រអប់ដែលមានពណ៌ក្រហម' ?></small>
            </div>
        </div>
        <?php endif; ?>

        <div class="card shadow">
            <div class="card-header bg-danger text-white">
                <i class="bi bi-clipboard2-check me-2"></i>
                <strong>EX12 — Form Validation ជាមួយ Regex</strong>
            </div>
            <div class="card-body p-4">
                <p class="text-muted mb-4 small">
                    <i class="bi bi-info-circle me-1"></i>
                    ប្រើ <code>preg_match()</code> ផ្ទៀងផ្ទាត់ Username, Email, Phone, Password ជាមួយ Regular Expression
                </p>

                <form method="POST" action="" novalidate>

                    <!-- 1. Username -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <span class="badge bg-primary me-2">1</span>
                            ឈ្មោះអ្នកប្រើ (Username)
                        </label>
                        <input type="text" name="username" id="username"
                               class="form-control <?= $submitted ? ($results['username']['valid'] ? 'is-valid' : 'is-invalid') : '' ?>"
                               value="<?= htmlspecialchars($formData['username'] ?? '') ?>"
                               placeholder="ឧ. ThornPanha_2026 (4-20 តួ)">
                        <div class="mt-1 small text-muted">
                            <i class="bi bi-regex me-1"></i>
                            Regex: <code><?= htmlspecialchars('/^[a-zA-Z0-9_]{4,20}$/') ?></code>
                        </div>
                        <?php if ($submitted): ?>
                        <div class="mt-1 small fw-bold text-<?= $results['username']['valid'] ? 'success' : 'danger' ?>">
                            <i class="bi bi-<?= $results['username']['valid'] ? 'check-circle-fill' : 'x-circle-fill' ?> me-1"></i>
                            <?= htmlspecialchars($results['username']['message']) ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- 2. Email -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <span class="badge bg-success me-2">2</span>
                            អាសយដ្ឋានអ៊ីមែល (Email)
                        </label>
                        <input type="email" name="email" id="email"
                               class="form-control <?= $submitted ? ($results['email']['valid'] ? 'is-valid' : 'is-invalid') : '' ?>"
                               value="<?= htmlspecialchars($formData['email'] ?? '') ?>"
                               placeholder="ឧ. panha.dev@example.com">
                        <div class="mt-1 small text-muted">
                            <i class="bi bi-regex me-1"></i>
                            Regex: <code><?= htmlspecialchars('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/') ?></code>
                        </div>
                        <?php if ($submitted): ?>
                        <div class="mt-1 small fw-bold text-<?= $results['email']['valid'] ? 'success' : 'danger' ?>">
                            <i class="bi bi-<?= $results['email']['valid'] ? 'check-circle-fill' : 'x-circle-fill' ?> me-1"></i>
                            <?= htmlspecialchars($results['email']['message']) ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- 3. Phone -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <span class="badge bg-warning text-dark me-2">3</span>
                            លេខទូរស័ព្ទខ្មែរ (Cambodia Phone)
                        </label>
                        <input type="text" name="phone" id="phone"
                               class="form-control <?= $submitted ? ($results['phone']['valid'] ? 'is-valid' : 'is-invalid') : '' ?>"
                               value="<?= htmlspecialchars($formData['phone'] ?? '') ?>"
                               placeholder="ឧ. 012345678 ឬ 0961234567 ឬ +85512345678">
                        <div class="mt-1 small text-muted">
                            <i class="bi bi-regex me-1"></i>
                            Regex: <code><?= htmlspecialchars('/^(?:\+855|0)(?:1[0-9]|6[0-9]|7[0-9]|8[0-9]|9[0-9])[0-9]{6,7}$/') ?></code>
                        </div>
                        <?php if ($submitted): ?>
                        <div class="mt-1 small fw-bold text-<?= $results['phone']['valid'] ? 'success' : 'danger' ?>">
                            <i class="bi bi-<?= $results['phone']['valid'] ? 'check-circle-fill' : 'x-circle-fill' ?> me-1"></i>
                            <?= htmlspecialchars($results['phone']['message']) ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- 4. Password -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <span class="badge bg-danger me-2">4</span>
                            ពាក្យសម្ងាត់ (Strong Password)
                        </label>
                        <div class="input-group">
                            <input type="password" name="password" id="password_field"
                                   class="form-control <?= $submitted ? ($results['password']['valid'] ? 'is-valid' : 'is-invalid') : '' ?>"
                                   placeholder="ឧ. Panha@2026 (≥ ៨ តួ)">
                            <button type="button" class="btn btn-outline-secondary" data-toggle-password="password_field">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <div class="mt-1 small text-muted">
                            <i class="bi bi-regex me-1"></i>
                            Regex: <code><?= htmlspecialchars('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/') ?></code>
                        </div>
                        <?php if ($submitted): ?>
                        <div class="mt-1 small fw-bold text-<?= $results['password']['valid'] ? 'success' : 'danger' ?>">
                            <i class="bi bi-<?= $results['password']['valid'] ? 'check-circle-fill' : 'x-circle-fill' ?> me-1"></i>
                            <?= htmlspecialchars($results['password']['message']) ?>
                        </div>
                        <?php if (!$results['password']['valid']): ?>
                        <?php $c = $results['password']['checks']; ?>
                        <ul class="list-unstyled mt-2 small ps-2">
                            <?php
                            $checkItems = [
                                'length'  => 'ប្រវែងយ៉ាងតិច ៨ តួ',
                                'upper'   => 'អក្សរធំ (A-Z) ≥ ១',
                                'lower'   => 'អក្សរតូច (a-z) ≥ ១',
                                'digit'   => 'ជាតួលេខ (0-9) ≥ ១',
                                'special' => 'និមិត្តសញ្ញា (@$!%*?&) ≥ ១',
                            ];
                            foreach ($checkItems as $key => $label): ?>
                            <li class="text-<?= $c[$key] ? 'success' : 'danger' ?>">
                                <i class="bi bi-<?= $c[$key] ? 'check-circle-fill' : 'circle' ?> me-1"></i>
                                <?= $label ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                        <?php endif; ?>
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex gap-3 flex-wrap">
                        <button type="submit" class="btn btn-danger px-4">
                            <i class="bi bi-shield-check me-2"></i>ផ្ទៀងផ្ទាត់ (Validate)
                        </button>
                        <button type="button" class="btn btn-outline-primary px-4" onclick="fillDemo()">
                            <i class="bi bi-magic me-2"></i>បំពេញទិន្នន័យគំរូ
                        </button>
                        <a href="ex12-validation.php" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>សើស
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Regex Reference Card -->
        <div class="card mt-4 border-0 bg-light">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2 text-danger"></i>សង្ខេប Regex Patterns</h6>
                <div class="row g-2 small">
                    <div class="col-md-6">
                        <div class="p-2 bg-white rounded-3 border">
                            <div class="fw-bold mb-1">Username</div>
                            <code class="text-primary">/^[a-zA-Z0-9_]{4,20}$/</code>
                            <div class="text-muted mt-1">^ = ដើម, $ = ចុង, {4,20} = ចន្លោះ 4-20</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-2 bg-white rounded-3 border">
                            <div class="fw-bold mb-1">Email</div>
                            <code class="text-success">/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/</code>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-2 bg-white rounded-3 border">
                            <div class="fw-bold mb-1">ទូរស័ព្ទខ្មែរ</div>
                            <code class="text-warning">/^(?:\+855|0)[0-9]{8,9}$/</code>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-2 bg-white rounded-3 border">
                            <div class="fw-bold mb-1">Password</div>
                            <code class="text-danger">/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!]).{8,}$/</code>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function fillDemo() {
    document.getElementById('username').value     = 'ThornPanha_26';
    document.getElementById('email').value        = 'panha.dev@gmail.com';
    document.getElementById('phone').value        = '0961234567';
    document.getElementById('password_field').value = 'Panha@2026';
}
</script>

<?php include '../includes/footer.php'; ?>
