<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$pageTitle = 'លំហាត់ ០១ — ឈ្មោះពេញ';
$result    = null;
$formData  = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = [
        'first_name'  => sanitize($_POST['first_name']  ?? ''),
        'last_name'   => sanitize($_POST['last_name']   ?? ''),
        'middle_name' => sanitize($_POST['middle_name'] ?? ''),
    ];

    if (!empty($formData['first_name']) && !empty($formData['last_name'])) {
        $fullName = formatFullName($formData['first_name'], $formData['last_name'], $formData['middle_name']);
        $initials = getNameInitials($fullName);
        $result   = compact('fullName', 'initials', 'formData');
    }
}

include '../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-7">

        <!-- Form Card -->
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <i class="bi bi-person-badge me-2"></i>
                <strong>EX01 — ទម្រង់រៀបចំឈ្មោះពេញ</strong>
            </div>
            <div class="card-body p-4">
                <p class="text-muted mb-4">
                    <i class="bi bi-info-circle me-1"></i>
                    បញ្ចូលឈ្មោះ ហើយប្រព័ន្ធនឹង Format ឱ្យបានត្រឹមត្រូវ ដោយ Capitalize ព្យាញ្ជនៈដំបូង
                    និងបង្ហាញ Initials។
                </p>

                <form method="POST" action="">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-person me-1 text-primary"></i>ឈ្មោះ <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="first_name" class="form-control"
                                   placeholder="ឧ. sokha / SOKHA / sokHA"
                                   value="<?= old('first_name', $formData) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-person me-1 text-success"></i>នាមត្រកូល <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="last_name" class="form-control"
                                   placeholder="ឧ. meas / MEAS"
                                   value="<?= old('last_name', $formData) ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">
                                <i class="bi bi-person-add me-1 text-info"></i>ឈ្មោះកណ្តាល
                                <small class="text-muted">(ស្រេចចិត្ត)</small>
                            </label>
                            <input type="text" name="middle_name" class="form-control"
                                   placeholder="ឧ. dara"
                                   value="<?= old('middle_name', $formData) ?>">
                        </div>
                    </div>
                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check2 me-2"></i>ដំណើរការ
                        </button>
                        <a href="ex01-fullname.php" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>សើស
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Result -->
        <?php if ($result): ?>
        <div class="result-box success fade-in">
            <h5 class="fw-bold text-success mb-3">
                <i class="bi bi-check-circle-fill me-2"></i>លទ្ធផល
            </h5>
            <div class="row g-3">
                <div class="col-md-7">
                    <div class="p-3 bg-white rounded-3 shadow-sm">
                        <div class="text-muted small mb-1">ឈ្មោះពេញ (Formatted)</div>
                        <div class="result-value fs-3 fw-bold text-primary">
                            <?= htmlspecialchars($result['fullName']) ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="p-3 bg-white rounded-3 shadow-sm">
                        <div class="text-muted small mb-1">Initials</div>
                        <div class="result-value fs-3 fw-bold text-info">
                            <?= htmlspecialchars($result['initials']) ?>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <table class="table table-sm table-bordered mb-0">
                        <tr>
                            <th class="bg-primary text-white w-40">Input</th>
                            <th class="bg-primary text-white">Output</th>
                        </tr>
                        <tr>
                            <td>First Name</td>
                            <td><code><?= htmlspecialchars($result['formData']['first_name']) ?></code>
                                → <strong><?= ucwords(strtolower($result['formData']['first_name'])) ?></strong></td>
                        </tr>
                        <?php if (!empty($result['formData']['middle_name'])): ?>
                        <tr>
                            <td>Middle Name</td>
                            <td><code><?= htmlspecialchars($result['formData']['middle_name']) ?></code>
                                → <strong><?= ucwords(strtolower($result['formData']['middle_name'])) ?></strong></td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <td>Last Name</td>
                            <td><code><?= htmlspecialchars($result['formData']['last_name']) ?></code>
                                → <strong><?= ucwords(strtolower($result['formData']['last_name'])) ?></strong></td>
                        </tr>
                        <tr>
                            <td>Full Name</td>
                            <td><strong class="text-primary"><?= htmlspecialchars($result['fullName']) ?></strong></td>
                        </tr>
                        <tr>
                            <td>Initials</td>
                            <td><strong class="text-info"><?= htmlspecialchars($result['initials']) ?></strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Explanation Card -->
        <div class="card mt-4 border-0 bg-light">
            <div class="card-body">
                <h6 class="fw-bold mb-2"><i class="bi bi-code-slash me-2 text-primary"></i>Functions ប្រើ</h6>
                <ul class="mb-0 small">
                    <li><code>formatFullName(firstName, lastName, middleName)</code> — Format ឈ្មោះ</li>
                    <li><code>ucwords(strtolower(...))</code> — Capitalize ព្យាញ្ជនៈដំបូងគ្រប់ word</li>
                    <li><code>getNameInitials(fullName)</code> — ស្រង់ Initial ពីឈ្មោះពេញ</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
