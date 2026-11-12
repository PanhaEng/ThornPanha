</div><!-- /container -->
</main>

<footer class="footer mt-auto py-4 bg-dark text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h6 class="mb-1 fw-bold">
                    <i class="bi bi-code-slash text-primary me-2"></i><?= APP_NAME ?>
                </h6>
                <small class="text-muted">PHP Functions &amp; Forms — Midterm Project <?= date('Y') ?></small>
            </div>
            <div class="col-md-6 text-md-end mt-2 mt-md-0">
                <small class="text-muted">
                    <i class="bi bi-person-fill me-1 text-info"></i>
                    បង្កើតដោយ <strong class="text-white">ThornPanha</strong>
                    &nbsp;|&nbsp;
                    <i class="bi bi-calendar3 me-1 text-warning"></i>
                    <?= date('Y') ?>
                </small>
                <div class="mt-1">
                    <span class="badge bg-secondary me-1">PHP 8.x</span>
                    <span class="badge bg-primary me-1">Bootstrap 5</span>
                    <span class="badge bg-success">12 Exercises</span>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="<?= $jsPath ?? '../assets/js/main.js' ?>"></script>
</body>
</html>
