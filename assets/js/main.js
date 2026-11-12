/**
 * main.js — PHP Functions & Forms Midterm Project
 * Helper scripts for UI enhancements
 */

document.addEventListener('DOMContentLoaded', function () {

    // =========================================================
    // 1. Password Visibility Toggle
    // =========================================================
    document.querySelectorAll('[data-toggle-password]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var targetId = this.getAttribute('data-toggle-password');
            var input = document.getElementById(targetId);
            if (!input) return;
            var icon = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                if (icon) { icon.classList.remove('bi-eye'); icon.classList.add('bi-eye-slash'); }
            } else {
                input.type = 'password';
                if (icon) { icon.classList.remove('bi-eye-slash'); icon.classList.add('bi-eye'); }
            }
        });
    });

    // =========================================================
    // 2. Live Password Strength Meter (EX05)
    // =========================================================
    var pwdInput = document.getElementById('live_password');
    if (pwdInput) {
        pwdInput.addEventListener('input', function () {
            var val = this.value;
            var score = 0;
            var criteria = {
                length:    val.length >= 8,
                uppercase: /[A-Z]/.test(val),
                lowercase: /[a-z]/.test(val),
                number:    /[0-9]/.test(val),
                special:   /[\W_]/.test(val),
            };
            for (var k in criteria) { if (criteria[k]) score++; }

            // Update bar
            var fill = document.getElementById('strength_fill');
            var label = document.getElementById('strength_label');
            var pct = (score / 5) * 100;
            var colors = ['#dc3545','#fd7e14','#ffc107','#0d6efd','#198754'];
            var labels = ['ខ្សោយខ្លាំង','ខ្សោយ','មធ្យម','ខ្លាំង','ខ្លាំងខ្លាំង'];

            if (fill) {
                fill.style.width = pct + '%';
                fill.style.background = colors[Math.max(0, score - 1)] || '#dee2e6';
            }
            if (label && val.length > 0) {
                label.textContent = labels[Math.max(0, score - 1)] || '';
            } else if (label) {
                label.textContent = '';
            }

            // Update checklist
            var checks = {
                'chk_length':    criteria.length,
                'chk_uppercase': criteria.uppercase,
                'chk_lowercase': criteria.lowercase,
                'chk_number':    criteria.number,
                'chk_special':   criteria.special,
            };
            for (var id in checks) {
                var el = document.getElementById(id);
                if (!el) continue;
                var icon = el.querySelector('i');
                if (checks[id]) {
                    el.classList.add('text-success');
                    el.classList.remove('text-muted');
                    if (icon) { icon.className = 'bi bi-check-circle-fill me-1'; }
                } else {
                    el.classList.remove('text-success');
                    el.classList.add('text-muted');
                    if (icon) { icon.className = 'bi bi-circle me-1'; }
                }
            }
        });
    }

    // =========================================================
    // 3. Number Input Formatter (comma on blur)
    // =========================================================
    document.querySelectorAll('.format-number').forEach(function (input) {
        input.addEventListener('blur', function () {
            var val = parseFloat(this.value.replace(/,/g, ''));
            if (!isNaN(val)) this.value = val.toLocaleString();
        });
        input.addEventListener('focus', function () {
            this.value = this.value.replace(/,/g, '');
        });
    });

    // =========================================================
    // 4. Auto-dismiss Alerts
    // =========================================================
    document.querySelectorAll('.alert-auto-dismiss').forEach(function (alert) {
        setTimeout(function () {
            var bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 4000);
    });

    // =========================================================
    // 5. Fade-in on Result Sections
    // =========================================================
    document.querySelectorAll('.result-box, .result-section').forEach(function (el) {
        el.classList.add('fade-in');
    });

    // =========================================================
    // 6. Confirm Delete for Todo
    // =========================================================
    document.querySelectorAll('[data-confirm]').forEach(function (el) {
        el.addEventListener('click', function (e) {
            if (!confirm(this.getAttribute('data-confirm'))) {
                e.preventDefault();
            }
        });
    });

    // =========================================================
    // 7. Tooltip initialization
    // =========================================================
    var tooltipEls = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipEls.forEach(function (el) {
        new bootstrap.Tooltip(el);
    });

    // =========================================================
    // 8. Live character counter
    // =========================================================
    document.querySelectorAll('[data-char-count]').forEach(function (input) {
        var targetId = input.getAttribute('data-char-count');
        var display  = document.getElementById(targetId);
        if (!display) return;
        input.addEventListener('input', function () {
            display.textContent = this.value.length;
        });
    });

});
