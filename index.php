<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$pageTitle = APP_NAME . ' — Dashboard';
$cssPath   = 'assets/css/style.css';
$jsPath    = 'assets/js/main.js';
$rootPath  = '';
?>
<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Battambang:wght@300;400;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="<?= $cssPath ?>" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">
            <i class="bi bi-code-slash me-2"></i><?= APP_NAME ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link active" href="index.php"><i class="bi bi-house-fill me-1"></i>ទំព័រដើម</a></li>
            </ul>
            <span class="navbar-text ms-3 text-warning fw-bold">
                <i class="bi bi-mortarboard-fill me-1"></i>Midterm 2026
            </span>
        </div>
    </div>
</nav>

<main class="py-4">
<div class="container">

    <!-- Hero -->
    <div class="hero-section mb-4 shadow">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="display-5 fw-bold mb-2">
                    <i class="bi bi-braces-asterisk me-3"></i>PHP Functions &amp; Forms
                </h1>
                <p class="lead mb-3 opacity-90">
                    ប្រូជេក Midterm — ការអនុវត្ត PHP Functions, Forms, Session, Regex និង Validation
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge bg-white text-primary fs-6 px-3 py-2 rounded-pill">
                        <i class="bi bi-filetype-php me-1"></i>PHP 8.x
                    </span>
                    <span class="badge bg-white text-success fs-6 px-3 py-2 rounded-pill">
                        <i class="bi bi-bootstrap me-1"></i>Bootstrap 5
                    </span>
                    <span class="badge bg-white text-warning fs-6 px-3 py-2 rounded-pill">
                        <i class="bi bi-grid-fill me-1"></i>12 Exercises
                    </span>
                </div>
            </div>
            <div class="col-md-4 text-center mt-3 mt-md-0">
                <div class="row g-2">
                    <div class="col-6">
                        <div class="stat-box">
                            <div class="stat-number">12</div>
                            <div class="small opacity-75">លំហាត់</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-box">
                            <div class="stat-number">PHP</div>
                            <div class="small opacity-75">ភាសា</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Exercise Grid -->
    <div class="row mb-3">
        <div class="col">
            <h4 class="fw-bold mb-1"><i class="bi bi-grid-fill me-2 text-primary"></i>បញ្ជីលំហាត់ទាំងអស់</h4>
            <p class="text-muted small">ចុចលើកាតខាងក្រោមដើម្បីចូលលំហាត់</p>
        </div>
    </div>

    <?php
    $exercises = [
        ['num'=>'01','file'=>'ex01-fullname.php',   'icon'=>'bi-person-badge',      'color'=>'primary',  'title'=>'ឈ្មោះពេញ',              'desc'=>'រៀបចំ និងដំណើរការ First/Middle/Last Name'],
        ['num'=>'02','file'=>'ex02-calculator.php', 'icon'=>'bi-calculator',         'color'=>'success',  'title'=>'ម៉ាស៊ីនគិតលេខ',         'desc'=>'+ − × ÷ % ** គ្រប់ប្រតិបត្តិករ'],
        ['num'=>'03','file'=>'ex03-temperature.php','icon'=>'bi-thermometer-half',   'color'=>'danger',   'title'=>'បំប្លែងសីតុណ្ហភាព',     'desc'=>'Celsius ↔ Fahrenheit ↔ Kelvin'],
        ['num'=>'04','file'=>'ex04-cafe-bill.php',  'icon'=>'bi-cup-hot-fill',       'color'=>'warning',  'title'=>'វិក្កយបត្រកាហ្វេ',      'desc'=>'គណនា Subtotal + Tax + Discount'],
        ['num'=>'05','file'=>'ex05-password.php',   'icon'=>'bi-shield-lock-fill',   'color'=>'info',     'title'=>'ពិនិត្យពាក្យសម្ងាត់',  'desc'=>'ស្ទង់កម្រិតភាពខ្លាំង Password Strength'],
        ['num'=>'06','file'=>'ex06-palindrome.php', 'icon'=>'bi-arrow-left-right',   'color'=>'purple',   'title'=>'Palindrome',              'desc'=>'រាប់ពាក្យ ឧបសគ្គ ឆែក Palindrome'],
        ['num'=>'07','file'=>'ex07-bmi.php',        'icon'=>'bi-activity',           'color'=>'teal',     'title'=>'BMI Calculator',          'desc'=>'គណនា Body Mass Index + ចំណាត់ថ្នាក់'],
        ['num'=>'08','file'=>'ex08-gradebook.php',  'icon'=>'bi-journal-bookmark',   'color'=>'orange',   'title'=>'Gradebook',               'desc'=>'ពិន្ទុ → Grade A/B/C + GPA + Statistics'],
        ['num'=>'09','file'=>'ex09-search.php',     'icon'=>'bi-search',             'color'=>'cyan',     'title'=>'ស្វែងរកទំនិញ',          'desc'=>'Search ទំនិញក្នុង Stock ជាមួយ Filter'],
        ['num'=>'10','file'=>'ex10-todo.php',       'icon'=>'bi-check2-square',      'color'=>'indigo',   'title'=>'Todo List',               'desc'=>'CRUD ដោយប្រើ PHP Session'],
        ['num'=>'11','file'=>'ex11-currency.php',   'icon'=>'bi-currency-exchange',  'color'=>'green',    'title'=>'ប្តូរប្រាក់',            'desc'=>'USD ↔ KHR ↔ THB ↔ EUR ↔ CNY + ច្រើន'],
        ['num'=>'12','file'=>'ex12-validation.php', 'icon'=>'bi-clipboard2-check',   'color'=>'rose',     'title'=>'Form Validation',         'desc'=>'Regex ផ្ទៀងផ្ទាត់ Email Phone Password'],
    ];

    $colorMap = [
        'primary'=>['bg-primary','text-white'],   'success'=>['bg-success','text-white'],
        'danger' =>['bg-danger','text-white'],    'warning'=>['bg-warning','text-dark'],
        'info'   =>['bg-info','text-dark'],       'purple' =>['','text-purple'],
        'teal'   =>['','text-teal'],              'orange' =>['','text-orange'],
        'cyan'   =>['bg-info','text-dark'],       'indigo' =>['','text-indigo'],
        'green'  =>['bg-success','text-white'],   'rose'   =>['bg-danger','text-white'],
    ];

    $btnColorMap = [
        'primary'=>'btn-primary', 'success'=>'btn-success', 'danger'=>'btn-danger',
        'warning'=>'btn-warning', 'info'=>'btn-info',       'purple'=>'btn-primary',
        'teal'=>'btn-success',    'orange'=>'btn-warning',  'cyan'=>'btn-info',
        'indigo'=>'btn-primary',  'green'=>'btn-success',   'rose'=>'btn-danger',
    ];
    ?>

    <div class="row g-3">
        <?php foreach ($exercises as $ex): ?>
        <div class="col-sm-6 col-lg-4">
            <div class="card h-100 exercise-card shadow-sm border-0">
                <div class="card-body d-flex gap-3 align-items-start">
                    <div>
                        <span class="badge bg-primary mb-2 fs-6 px-3"><?= $ex['num'] ?></span>
                        <h6 class="fw-bold mb-1"><?= $ex['title'] ?></h6>
                        <p class="text-muted small mb-3"><?= $ex['desc'] ?></p>
                        <a href="exercises/<?= $ex['file'] ?>"
                           class="btn btn-sm <?= $btnColorMap[$ex['color']] ?> rounded-pill px-3">
                            <i class="bi <?= $ex['icon'] ?> me-1"></i>ចូលលំហាត់
                        </a>
                    </div>
                    <div class="ms-auto text-end">
                        <i class="bi <?= $ex['icon'] ?> exercise-icon text-primary opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Footer Info -->
    <div class="mt-5 text-center text-muted small">
        <p class="mb-1">
            <i class="bi bi-info-circle me-1"></i>
            ប្រូជេកនេះប្រើ <strong>PHP <?= phpversion() ?></strong>,
            Bootstrap 5, Session, Regex, Form Validation
        </p>
    </div>

</div>
</main>

<footer class="footer mt-auto py-4 bg-dark text-white">
    <div class="container text-center">
        <small class="text-muted">
            <i class="bi bi-person-fill me-1 text-info"></i>
            បង្កើតដោយ <strong class="text-white">ThornPanha</strong>
            &nbsp;|&nbsp;
            <i class="bi bi-calendar3 me-1 text-warning"></i><?= date('Y') ?>
            &nbsp;|&nbsp;
            <span class="badge bg-secondary">PHP <?= phpversion() ?></span>
        </small>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= $jsPath ?>"></script>
</body>
</html>
