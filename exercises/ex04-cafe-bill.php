<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$pageTitle = 'លំហាត់ ០៤ — វិក្កយបត្រកាហ្វេ';
$bill      = null;
$formData  = [];

$menuItems = [
    ['id'=>'coffee',  'name'=>'កាហ្វេ',          'price'=>2.00],
    ['id'=>'tea',     'name'=>'តែ',               'price'=>1.50],
    ['id'=>'juice',   'name'=>'ទឹកក្រូច',         'price'=>1.75],
    ['id'=>'cake',    'name'=>'នំខេក',            'price'=>3.00],
    ['id'=>'sandwich','name'=>'Sandwich',          'price'=>4.50],
    ['id'=>'water',   'name'=>'ទឹកស្អាត',         'price'=>0.75],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedItems = [];
    foreach ($menuItems as $menu) {
        $qty = (int)($_POST['qty_' . $menu['id']] ?? 0);
        if ($qty > 0) {
            $selectedItems[] = ['name' => $menu['name'], 'price' => $menu['price'], 'qty' => $qty];
        }
    }
    $discountPct = max(0, min(100, (float)($_POST['discount'] ?? 0)));
    $taxPct      = max(0, min(50,  (float)($_POST['tax']      ?? 10)));

    if (!empty($selectedItems)) {
        $bill = calculateCafeBill($selectedItems, $discountPct, $taxPct);
    }
    $formData = $_POST;
}

include '../includes/header.php';
?>

<div class="row g-4">
    <!-- Order Form -->
    <div class="col-lg-6">
        <div class="card shadow h-100">
            <div class="card-header bg-warning text-dark">
                <i class="bi bi-cup-hot-fill me-2"></i>
                <strong>EX04 — ការ៉ុតក្រូចកាហ្វេ Menu</strong>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="">
                    <h6 class="fw-bold mb-3 text-muted">
                        <i class="bi bi-list-ul me-1"></i>ជ្រើសរើសម្ហូប
                    </h6>
                    <?php foreach ($menuItems as $menu): ?>
                    <div class="d-flex align-items-center mb-3 p-2 border rounded-3 bg-light">
                        <div class="flex-grow-1">
                            <div class="fw-semibold"><?= $menu['name'] ?></div>
                            <small class="text-muted">$<?= number_format($menu['price'], 2) ?> / ដង</small>
                        </div>
                        <div style="width:100px">
                            <input type="number" name="qty_<?= $menu['id'] ?>"
                                   class="form-control form-control-sm text-center"
                                   min="0" max="20"
                                   value="<?= (int)($formData['qty_'.$menu['id']] ?? 0) ?>"
                                   placeholder="0">
                        </div>
                    </div>
                    <?php endforeach; ?>

                    <hr>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">
                                <i class="bi bi-percent text-success me-1"></i>បញ្ចុះតម្លៃ (%)
                            </label>
                            <input type="number" name="discount" class="form-control"
                                   min="0" max="100" step="0.1"
                                   value="<?= htmlspecialchars($formData['discount'] ?? '0') ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">
                                <i class="bi bi-receipt text-warning me-1"></i>ពន្ធ Tax (%)
                            </label>
                            <input type="number" name="tax" class="form-control"
                                   min="0" max="50" step="0.1"
                                   value="<?= htmlspecialchars($formData['tax'] ?? '10') ?>">
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-warning px-4">
                            <i class="bi bi-receipt-cutoff me-2"></i>គណនាវិក្កយបត្រ
                        </button>
                        <a href="ex04-cafe-bill.php" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>សើស
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bill Result -->
    <div class="col-lg-6">
        <?php if ($bill): ?>
        <div class="card shadow fade-in">
            <div class="card-header bg-dark text-white">
                <i class="bi bi-receipt me-2"></i><strong>វិក្កយបត្រ</strong>
            </div>
            <div class="card-body p-0">
                <div class="p-4 text-center border-bottom">
                    <div class="fs-5 fw-bold">☕ Cafe Receipt ☕</div>
                    <div class="text-muted small"><?= date('d/m/Y H:i:s') ?></div>
                </div>
                <div class="p-3">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>ម្ហូប</th>
                                <th class="text-center">ចំនួន</th>
                                <th class="text-end">តម្លៃ</th>
                                <th class="text-end">សរុប</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bill['lineItems'] as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['name']) ?></td>
                                <td class="text-center"><?= $item['qty'] ?></td>
                                <td class="text-end">$<?= number_format($item['price'], 2) ?></td>
                                <td class="text-end fw-semibold">$<?= number_format($item['lineTotal'], 2) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="px-4 pb-4">
                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Subtotal</span>
                            <span>$<?= number_format($bill['subtotal'], 2) ?></span>
                        </div>
                        <?php if ($bill['discountPct'] > 0): ?>
                        <div class="d-flex justify-content-between mb-1 text-success">
                            <span>បញ្ចុះ <?= $bill['discountPct'] ?>%</span>
                            <span>−$<?= number_format($bill['discountAmt'], 2) ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="d-flex justify-content-between mb-2 text-warning">
                            <span>Tax <?= $bill['taxPct'] ?>%</span>
                            <span>+$<?= number_format($bill['taxAmt'], 2) ?></span>
                        </div>
                        <div class="d-flex justify-content-between border-top pt-2">
                            <span class="fw-bold fs-5">សរុបទាំងអស់</span>
                            <span class="fw-bold fs-5 text-danger">$<?= number_format($bill['grandTotal'], 2) ?></span>
                        </div>
                    </div>
                    <div class="text-center mt-3 text-muted small">
                        <i class="bi bi-heart-fill text-danger"></i> អរគុណសម្រាប់ការទិញ! <i class="bi bi-heart-fill text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="card border-0 bg-light h-100 d-flex align-items-center justify-content-center">
            <div class="text-center text-muted p-4">
                <i class="bi bi-receipt fs-1 d-block mb-2"></i>
                <p>ជ្រើសរើសម្ហូប ហើយចុចគណនា</p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
