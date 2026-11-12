<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$pageTitle = 'លំហាត់ ១១ — ប្តូរប្រាក់';

// Currency data
$currencies = [
    'USD' => ['symbol'=>'$',   'name'=>'ដុល្លារអាមេរិក', 'flag'=>'🇺🇸', 'rate'=>1.0],
    'KHR' => ['symbol'=>'៛',   'name'=>'រៀលខ្មែរ',       'flag'=>'🇰🇭', 'rate'=>4100.0],
    'THB' => ['symbol'=>'฿',   'name'=>'បាតថៃ',           'flag'=>'🇹🇭', 'rate'=>34.50],
    'EUR' => ['symbol'=>'€',   'name'=>'អឺរ៉ូ',            'flag'=>'🇪🇺', 'rate'=>0.92],
    'CNY' => ['symbol'=>'¥',   'name'=>'យន់ចិន',          'flag'=>'🇨🇳', 'rate'=>7.24],
    'JPY' => ['symbol'=>'¥',   'name'=>'យ៉េនជប៉ុន',       'flag'=>'🇯🇵', 'rate'=>153.20],
    'VND' => ['symbol'=>'₫',   'name'=>'ដុងវៀតណាម',      'flag'=>'🇻🇳', 'rate'=>25400.0],
    'SGD' => ['symbol'=>'S$',  'name'=>'ដុល្លារសិង្ហបុរី', 'flag'=>'🇸🇬', 'rate'=>1.34],
];

// Swap button
if (isset($_POST['swap'])) {
    $tmp  = $_POST['from_currency'] ?? 'USD';
    $_POST['from_currency'] = $_POST['to_currency'] ?? 'KHR';
    $_POST['to_currency']   = $tmp;
}

$amount = isset($_POST['amount']) && $_POST['amount'] !== '' ? (float)$_POST['amount'] : 100.0;
$from   = $_POST['from_currency'] ?? 'USD';
$to     = $_POST['to_currency']   ?? 'KHR';

// Validate keys
if (!isset($currencies[$from])) $from = 'USD';
if (!isset($currencies[$to]))   $to   = 'KHR';

// Convert
function localConvert(float $amount, string $from, string $to, array $cur): float {
    $inUSD = $amount / $cur[$from]['rate'];
    return $inUSD * $cur[$to]['rate'];
}

function localFormat(float $val, string $code, array $cur): string {
    $sym = $cur[$code]['symbol'];
    $dec = in_array($code, ['KHR','VND','JPY']) ? 0 : 2;
    return $sym . ' ' . number_format($val, $dec);
}

$converted   = localConvert($amount, $from, $to, $currencies);
$singleRate  = localConvert(1.0, $from, $to, $currencies);

include '../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-9">

        <!-- Form Card -->
        <div class="card shadow mb-4">
            <div class="card-header bg-success text-white">
                <i class="bi bi-currency-exchange me-2"></i>
                <strong>EX11 — កម្មវិធីប្ដូរប្រាក់អន្តរជាតិ</strong>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="">
                    <div class="row g-3 align-items-end">
                        <!-- Amount -->
                        <div class="col-md-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-cash-stack me-1 text-success"></i>
                                ចំនួនប្រាក់ <span class="text-danger">*</span>
                            </label>
                            <input type="number" step="any" min="0" name="amount"
                                   class="form-control form-control-lg"
                                   value="<?= htmlspecialchars((string)$amount) ?>" required>
                        </div>

                        <!-- From -->
                        <div class="col-md-3">
                            <label class="form-label fw-bold">
                                <i class="bi bi-arrow-right-circle me-1 text-warning"></i>ពី
                            </label>
                            <select name="from_currency" class="form-select form-select-lg">
                                <?php foreach ($currencies as $code => $info): ?>
                                <option value="<?= $code ?>" <?= $from === $code ? 'selected' : '' ?>>
                                    <?= $info['flag'] ?> <?= $code ?> — <?= $info['name'] ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Swap Button -->
                        <div class="col-md-1 text-center">
                            <button type="submit" name="swap" value="1"
                                    class="btn btn-outline-secondary btn-lg w-100"
                                    title="ប្ដូរទិសដៅ">
                                <i class="bi bi-arrow-left-right"></i>
                            </button>
                        </div>

                        <!-- To -->
                        <div class="col-md-3">
                            <label class="form-label fw-bold">
                                <i class="bi bi-arrow-left-circle me-1 text-info"></i>ទៅ
                            </label>
                            <select name="to_currency" class="form-select form-select-lg">
                                <?php foreach ($currencies as $code => $info): ?>
                                <option value="<?= $code ?>" <?= $to === $code ? 'selected' : '' ?>>
                                    <?= $info['flag'] ?> <?= $code ?> — <?= $info['name'] ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Submit -->
                        <div class="col-md-1">
                            <button type="submit" name="convert" class="btn btn-success btn-lg w-100">
                                <i class="bi bi-calculator"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Result Highlight -->
        <div class="result-box success mb-4 fade-in">
            <div class="text-center py-2">
                <div class="text-muted mb-1">
                    <span class="fs-5"><?= $currencies[$from]['flag'] ?></span>
                    <strong><?= localFormat($amount, $from, $currencies) ?> <?= $from ?></strong>
                    <i class="bi bi-arrow-right mx-2 text-success"></i>
                </div>
                <div class="result-value text-success">
                    <?= localFormat($converted, $to, $currencies) ?>
                </div>
                <div class="text-muted small mt-1">
                    <?= $currencies[$to]['flag'] ?> <?= $to ?> — <?= $currencies[$to]['name'] ?>
                </div>
                <div class="mt-2 p-2 bg-white rounded-3 d-inline-block small">
                    <i class="bi bi-info-circle me-1 text-info"></i>
                    <strong>អត្រា:</strong>
                    1 <?= $from ?> = <?= localFormat($singleRate, $to, $currencies) ?> <?= $to ?>
                </div>
            </div>
        </div>

        <!-- All Currencies Grid -->
        <h5 class="fw-bold mb-3">
            <i class="bi bi-grid me-2 text-success"></i>
            តារាងប្ដូរប្រាក់ពី <?= $currencies[$from]['flag'] ?> <?= localFormat($amount, $from, $currencies) ?> <?= $from ?>
        </h5>
        <div class="row g-3 mb-4">
            <?php foreach ($currencies as $code => $info):
                if ($code === $from) continue;
                $val = localConvert($amount, $from, $code, $currencies);
                $isActive = ($code === $to);
            ?>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="card text-center h-100 border-<?= $isActive ? 'success' : '0' ?> shadow-sm <?= $isActive ? 'bg-success bg-opacity-10' : '' ?>">
                    <div class="card-body py-3 px-2">
                        <div class="fs-2"><?= $info['flag'] ?></div>
                        <div class="fw-bold small text-muted"><?= $code ?></div>
                        <div class="fw-bold fs-5 mt-1 <?= $isActive ? 'text-success' : '' ?>">
                            <?= localFormat($val, $code, $currencies) ?>
                        </div>
                        <div class="text-muted" style="font-size:.7rem;"><?= $info['name'] ?></div>
                        <?php if ($isActive): ?>
                        <span class="badge bg-success mt-1">ជ្រើសរើស</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Exchange Rate Table -->
        <div class="card shadow border-0">
            <div class="card-header bg-dark text-white">
                <i class="bi bi-table me-2"></i><strong>អត្រាប្ដូរប្រាក់ទូទៅ (ទៅ USD)</strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle mb-0">
                        <thead>
                            <tr>
                                <th>រូបិយប័ណ្ណ</th>
                                <th>ឈ្មោះ</th>
                                <th class="text-end">1 USD =</th>
                                <th class="text-end">1 ឯកតា = USD</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($currencies as $code => $info): ?>
                            <tr <?= $code === $from || $code === $to ? 'class="table-active fw-bold"' : '' ?>>
                                <td>
                                    <span class="fs-5 me-2"><?= $info['flag'] ?></span>
                                    <strong><?= $code ?></strong>
                                </td>
                                <td><?= $info['name'] ?></td>
                                <td class="text-end">
                                    <?= $info['symbol'] ?> <?= number_format($info['rate'], in_array($code,['KHR','VND','JPY']) ? 0 : 4) ?>
                                </td>
                                <td class="text-end text-success">
                                    $ <?= number_format(1 / $info['rate'], 6) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card mt-4 border-0 bg-light">
            <div class="card-body small">
                <h6 class="fw-bold"><i class="bi bi-code-slash me-2 text-success"></i>Functions ប្រើ</h6>
                <ul class="mb-0">
                    <li><code>convertCurrency(amount, from, to)</code> — ប្ដូរតាមអត្រា USD base rate</li>
                    <li><code>getCurrencySymbol(code)</code> — បង្ហាញនិមិត្ត ($, ៛, ฿, €...)</li>
                    <li><code>formatMoney(amount, currency)</code> — Format ចំនួន + Symbol</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
