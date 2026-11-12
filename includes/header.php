<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? APP_NAME ?></title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts: Battambang (Khmer) + Roboto -->
    <link href="https://fonts.googleapis.com/css2?family=Battambang:wght@300;400;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= $cssPath ?? '../assets/css/style.css' ?>" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= $rootPath ?? '../' ?>index.php">
            <i class="bi bi-code-slash me-2"></i><?= APP_NAME ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="<?= $rootPath ?? '../' ?>index.php">
                        <i class="bi bi-house-fill me-1"></i>ទំព័រដើម
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-grid-fill me-1"></i>លំហាត់
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= $rootPath ?? '../' ?>exercises/ex01-fullname.php"><span class="badge bg-primary me-1">01</span> ឈ្មោះពេញ</a></li>
                        <li><a class="dropdown-item" href="<?= $rootPath ?? '../' ?>exercises/ex02-calculator.php"><span class="badge bg-primary me-1">02</span> ម៉ាស៊ីនគិតលេខ</a></li>
                        <li><a class="dropdown-item" href="<?= $rootPath ?? '../' ?>exercises/ex03-temperature.php"><span class="badge bg-primary me-1">03</span> បំប្លែងសីតុណ្ហភាព</a></li>
                        <li><a class="dropdown-item" href="<?= $rootPath ?? '../' ?>exercises/ex04-cafe-bill.php"><span class="badge bg-primary me-1">04</span> វិក្កយបត្រកាហ្វេ</a></li>
                        <li><a class="dropdown-item" href="<?= $rootPath ?? '../' ?>exercises/ex05-password.php"><span class="badge bg-primary me-1">05</span> ពិនិត្យពាក្យសម្ងាត់</a></li>
                        <li><a class="dropdown-item" href="<?= $rootPath ?? '../' ?>exercises/ex06-palindrome.php"><span class="badge bg-primary me-1">06</span> Palindrome</a></li>
                        <li><a class="dropdown-item" href="<?= $rootPath ?? '../' ?>exercises/ex07-bmi.php"><span class="badge bg-primary me-1">07</span> BMI</a></li>
                        <li><a class="dropdown-item" href="<?= $rootPath ?? '../' ?>exercises/ex08-gradebook.php"><span class="badge bg-primary me-1">08</span> Gradebook</a></li>
                        <li><a class="dropdown-item" href="<?= $rootPath ?? '../' ?>exercises/ex09-search.php"><span class="badge bg-primary me-1">09</span> ស្វែងរក</a></li>
                        <li><a class="dropdown-item" href="<?= $rootPath ?? '../' ?>exercises/ex10-todo.php"><span class="badge bg-primary me-1">10</span> Todo List</a></li>
                        <li><a class="dropdown-item" href="<?= $rootPath ?? '../' ?>exercises/ex11-currency.php"><span class="badge bg-primary me-1">11</span> ប្តូរប្រាក់</a></li>
                        <li><a class="dropdown-item" href="<?= $rootPath ?? '../' ?>exercises/ex12-validation.php"><span class="badge bg-primary me-1">12</span> Validation</a></li>
                    </ul>
                </li>
            </ul>
            <span class="navbar-text ms-3 text-warning">
                <i class="bi bi-mortarboard-fill me-1"></i>Midterm 2026
            </span>
        </div>
    </div>
</nav>

<!-- Page Header -->
<?php if (isset($pageTitle) && $pageTitle !== APP_NAME): ?>
<div class="page-header bg-gradient-primary text-white py-3">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="<?= $rootPath ?? '../' ?>index.php" class="text-white-50">
                        <i class="bi bi-house-fill"></i> ទំព័រដើម
                    </a>
                </li>
                <li class="breadcrumb-item active text-white"><?= $pageTitle ?></li>
            </ol>
        </nav>
        <h4 class="mb-0 mt-1"><?= $pageTitle ?></h4>
    </div>
</div>
<?php endif; ?>

<main class="py-4">
<div class="container">
