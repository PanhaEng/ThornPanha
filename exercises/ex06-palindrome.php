<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$pageTitle = 'លំហាត់ ០៦ — Palindrome & Word Count';
$result    = null;
$formData  = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = ['text' => $_POST['text'] ?? ''];
    $text = $formData['text'];

    if (!empty(trim($text))) {
        // Single word palindrome check
        $words = preg_split('/\s+/', trim($text));
        $palindromes = array_filter($words, fn($w) => strlen($w) >= 2 && isPalindrome($w));

        $result = [
            'text'         => $text,
            'wordCount'    => countWords($text),
            'charCount'    => countChars($text),
            'charNoSpace'  => countCharsNoSpace($text),
            'sentences'    => countSentences($text),
            'isPalindrome' => isPalindrome(preg_replace('/\s+/','',$text)),
            'palindromes'  => array_values($palindromes),
            'reversed'     => strrev(preg_replace('/[^a-zA-Z0-9]/', '', strtolower($text))),
        ];
    }
}

include '../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header" style="background:#6f42c1;" class="text-white">
                <i class="bi bi-arrow-left-right me-2"></i>
                <strong class="text-white">EX06 — រាប់ពាក្យ និងឆែក Palindrome</strong>
            </div>
            <div class="card-body p-4">
                <p class="text-muted mb-3">
                    <i class="bi bi-info-circle me-1"></i>
                    Palindrome = ពាក្យ ឬប្រយោគដែលអានពីមុខទៅក្រោយ ឬ ពីក្រោយទៅមុខ ដូចគ្នា
                    <br>ឧ: <strong>racecar, madam, level, A man a plan a canal Panama</strong>
                </p>
                <form method="POST" action="">
                    <label class="form-label fw-bold">
                        <i class="bi bi-textarea-t me-1" style="color:#6f42c1;"></i>
                        អត្ថបទ / ពាក្យ <span class="text-danger">*</span>
                    </label>
                    <textarea name="text" class="form-control mb-3" rows="4"
                              placeholder="ឧ. racecar&#10;ឬ: A man a plan a canal Panama"
                              data-char-count="char_live"
                              required><?= htmlspecialchars($formData['text'] ?? '') ?></textarea>
                    <div class="text-end text-muted small mb-3">
                        <i class="bi bi-fonts me-1"></i>តួ: <span id="char_live">0</span>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn px-4 text-white" style="background:#6f42c1;">
                            <i class="bi bi-search me-2"></i>វិភាគ
                        </button>
                        <a href="ex06-palindrome.php" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>សើស
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <?php if ($result): ?>
        <div class="row g-3 fade-in">
            <!-- Stats -->
            <div class="col-md-6">
                <div class="card shadow h-100">
                    <div class="card-header bg-primary text-white"><i class="bi bi-bar-chart me-2"></i>ស្ថិតិ</div>
                    <div class="card-body">
                        <table class="table table-bordered table-sm mb-0">
                            <tr><th>ពាក្យ</th><td class="fw-bold fs-5 text-primary"><?= $result['wordCount'] ?></td></tr>
                            <tr><th>តួ (ទាំង Space)</th><td class="fw-bold"><?= $result['charCount'] ?></td></tr>
                            <tr><th>តួ (គ្មាន Space)</th><td class="fw-bold"><?= $result['charNoSpace'] ?></td></tr>
                            <tr><th>ប្រយោគ</th><td class="fw-bold"><?= $result['sentences'] ?></td></tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Palindrome Check -->
            <div class="col-md-6">
                <div class="card shadow h-100">
                    <div class="card-header <?= $result['isPalindrome'] ? 'bg-success' : 'bg-danger' ?> text-white">
                        <i class="bi bi-arrow-left-right me-2"></i>Palindrome Check
                    </div>
                    <div class="card-body text-center py-4">
                        <div class="display-1 mb-2">
                            <?= $result['isPalindrome'] ? '✅' : '❌' ?>
                        </div>
                        <h4 class="fw-bold text-<?= $result['isPalindrome'] ? 'success' : 'danger' ?>">
                            <?= $result['isPalindrome'] ? 'យល់ព្រម! Palindrome' : 'មិនមែន Palindrome' ?>
                        </h4>
                        <div class="mt-3 p-2 bg-light rounded-3 small text-start">
                            <div class="text-muted mb-1">Cleaned &amp; Reversed:</div>
                            <code class="text-primary"><?= htmlspecialchars($result['reversed']) ?></code>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Palindrome words found -->
            <?php if (!empty($result['palindromes'])): ?>
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <i class="bi bi-check-all me-2"></i>ពាក្យ Palindrome ដែលរកឃើញ
                    </div>
                    <div class="card-body">
                        <?php foreach ($result['palindromes'] as $p): ?>
                            <span class="badge bg-success fs-6 me-2 mb-1 px-3"><?= htmlspecialchars($p) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <div class="card mt-4 border-0 bg-light">
            <div class="card-body small">
                <h6 class="fw-bold"><i class="bi bi-code-slash me-2" style="color:#6f42c1;"></i>Functions ប្រើ</h6>
                <ul class="mb-0">
                    <li><code>countWords($text)</code>, <code>countChars($text)</code>, <code>countSentences($text)</code></li>
                    <li><code>isPalindrome($str)</code> — ប្រើ <code>strrev()</code> + regex clean</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
