<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$pageTitle = 'លំហាត់ ០៩ — ស្វែងរកទំនិញ';
$results   = null;
$formData  = [];
$products  = getDefaultProducts();

// Get unique categories
$categories = array_unique(array_column($products, 'category'));
sort($categories);

if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['q'])) {
    $keyword  = sanitize($_POST['keyword'] ?? $_GET['q'] ?? '');
    $category = sanitize($_POST['category'] ?? $_GET['cat'] ?? '');
    $maxPrice = (float)($_POST['max_price'] ?? $_GET['max'] ?? 0);
    $inStock  = isset($_POST['in_stock']) || isset($_GET['stock']);

    $formData = compact('keyword','category','maxPrice','inStock');
    $results  = searchProducts($keyword, $products);

    // Additional filters
    if (!empty($category)) {
        $results = array_values(array_filter($results, fn($p) => $p['category'] === $category));
    }
    if ($maxPrice > 0) {
        $results = array_values(array_filter($results, fn($p) => $p['price'] <= $maxPrice));
    }
    if ($inStock) {
        $results = array_values(array_filter($results, fn($p) => $p['stock'] > 0));
    }
}

include '../includes/header.php';
?>

<div class="row g-4">
    <!-- Search Form -->
    <div class="col-lg-4">
        <div class="card shadow">
            <div class="card-header bg-info text-dark">
                <i class="bi bi-search me-2"></i><strong>EX09 — ស្វែងរក</strong>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-type me-1 text-info"></i>ពាក្យស្វែងរក
                        </label>
                        <input type="text" name="keyword" class="form-control"
                               placeholder="ឧ. ទឹក, coffee..."
                               value="<?= htmlspecialchars($formData['keyword'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-tags me-1 text-warning"></i>ប្រភេទ
                        </label>
                        <select name="category" class="form-select">
                            <option value="">ទាំងអស់</option>
                            <?php foreach ($categories as $cat): ?>
                            <option value="<?= htmlspecialchars($cat) ?>"
                                    <?= (($formData['category'] ?? '') === $cat) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-currency-dollar me-1 text-success"></i>តម្លៃអតិបរមា ($)
                        </label>
                        <input type="number" name="max_price" class="form-control"
                               step="0.25" min="0" placeholder="0 = មិនកំណត់"
                               value="<?= htmlspecialchars($formData['maxPrice'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="in_stock" id="inStock"
                                   <?= !empty($formData['inStock']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="inStock">
                                <i class="bi bi-box-seam me-1"></i>មានស្តុកតែប៉ុណ្ណោះ
                            </label>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-info px-4">
                            <i class="bi bi-search me-2"></i>ស្វែងរក
                        </button>
                        <a href="ex09-search.php" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg me-1"></i>សើស
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Category Summary -->
        <div class="card mt-3 border-0 bg-light">
            <div class="card-body small">
                <h6 class="fw-bold mb-2 text-muted">ប្រភេទទំនិញ</h6>
                <?php foreach ($categories as $cat): ?>
                <?php $cnt = count(array_filter($products, fn($p) => $p['category'] === $cat)); ?>
                <div class="d-flex justify-content-between mb-1">
                    <span><?= htmlspecialchars($cat) ?></span>
                    <span class="badge bg-info text-dark"><?= $cnt ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Results -->
    <div class="col-lg-8">
        <?php if ($results !== null): ?>
        <div class="fade-in">
            <div class="d-flex align-items-center mb-3">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-box-seam me-2 text-info"></i>
                    <?= count($results) ?> ទំនិញ
                </h5>
                <?php if (!empty($formData['keyword'])): ?>
                <span class="badge bg-info text-dark ms-2">
                    "<?= htmlspecialchars($formData['keyword']) ?>"
                </span>
                <?php endif; ?>
            </div>

            <?php if (empty($results)): ?>
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle me-2"></i>
                រកមិនឃើញទំនិញ ទៅតាមលក្ខខណ្ឌ
            </div>
            <?php else: ?>
            <div class="row g-3">
                <?php foreach ($results as $p): ?>
                <div class="col-sm-6 col-xl-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-info text-dark small"><?= htmlspecialchars($p['category']) ?></span>
                                <?php if ($p['stock'] <= 10 && $p['stock'] > 0): ?>
                                <span class="badge bg-warning text-dark small">ស្តុកជិតអស់</span>
                                <?php elseif ($p['stock'] == 0): ?>
                                <span class="badge bg-danger small">អស់ស្តុក</span>
                                <?php endif; ?>
                            </div>
                            <h6 class="fw-bold mb-1"><?= htmlspecialchars($p['name']) ?></h6>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="fs-5 fw-bold text-success">$<?= number_format($p['price'],2) ?></span>
                                <small class="text-muted">
                                    <i class="bi bi-box-seam me-1"></i><?= $p['stock'] ?> ដង
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php else: ?>
        <!-- Show all products by default -->
        <h5 class="fw-bold mb-3">
            <i class="bi bi-box-seam me-2 text-info"></i>ទំនិញទាំងអស់ (<?= count($products) ?>)
        </h5>
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ឈ្មោះ</th>
                        <th>ប្រភេទ</th>
                        <th class="text-end">តម្លៃ</th>
                        <th class="text-center">ស្តុក</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $p): ?>
                    <tr>
                        <td class="text-muted small"><?= $p['id'] ?></td>
                        <td class="fw-semibold"><?= htmlspecialchars($p['name']) ?></td>
                        <td><span class="badge bg-info text-dark"><?= htmlspecialchars($p['category']) ?></span></td>
                        <td class="text-end fw-bold text-success">$<?= number_format($p['price'],2) ?></td>
                        <td class="text-center">
                            <span class="badge <?= $p['stock'] > 50 ? 'bg-success' : ($p['stock'] > 10 ? 'bg-warning text-dark' : 'bg-danger') ?>">
                                <?= $p['stock'] ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
