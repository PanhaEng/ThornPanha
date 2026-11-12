<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$pageTitle = 'លំហាត់ ០៨ — Gradebook';
$students  = [];
$stats     = [];
$formData  = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $names  = $_POST['name']  ?? [];
    $scores = $_POST['score'] ?? [];

    foreach ($names as $i => $name) {
        $name  = sanitize($name);
        $score = isset($scores[$i]) ? max(0, min(100, (float)$scores[$i])) : 0;
        if (!empty($name)) {
            $grade = getGrade($score);
            $students[] = [
                'name'   => $name,
                'score'  => $score,
                'grade'  => $grade['grade'],
                'gpa'    => $grade['gpa'],
                'remark' => $grade['remark'],
                'color'  => $grade['color'],
            ];
        }
    }

    if (!empty($students)) {
        $allScores = array_column($students, 'score');
        $stats = calculateClassStats($allScores);
    }
    $formData = $_POST;
}

// Default 5 rows
$rowCount = max(5, count($students ?: ($formData['name'] ?? [])));

include '../includes/header.php';
?>

<div class="row g-4">
    <!-- Input Form -->
    <div class="col-lg-5">
        <div class="card shadow">
            <div class="card-header bg-warning text-dark">
                <i class="bi bi-journal-bookmark me-2"></i><strong>EX08 — បញ្ចូលពិន្ទុ</strong>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="" id="gradeForm">
                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>ឈ្មោះសិស្ស</th>
                                    <th>ពិន្ទុ (0-100)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for ($i = 0; $i < $rowCount; $i++): ?>
                                <tr>
                                    <td class="text-muted small"><?= $i+1 ?></td>
                                    <td>
                                        <input type="text" name="name[]" class="form-control form-control-sm"
                                               placeholder="ឈ្មោះ..."
                                               value="<?= htmlspecialchars($students[$i]['name'] ?? $formData['name'][$i] ?? '') ?>">
                                    </td>
                                    <td>
                                        <input type="number" name="score[]" class="form-control form-control-sm text-center"
                                               min="0" max="100" step="0.5"
                                               placeholder="0-100"
                                               value="<?= htmlspecialchars($students[$i]['score'] ?? $formData['score'][$i] ?? '') ?>">
                                    </td>
                                </tr>
                                <?php endfor; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex gap-2 mt-2">
                        <button type="submit" class="btn btn-warning px-4">
                            <i class="bi bi-graph-up me-2"></i>គណនា
                        </button>
                        <a href="ex08-gradebook.php" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>សើស
                        </a>
                    </div>
                </form>

                <!-- Grade Table Reference -->
                <div class="mt-4">
                    <h6 class="fw-bold text-muted small mb-2">ជួរ Grade</h6>
                    <table class="table table-sm table-bordered small mb-0">
                        <thead><tr><th>Grade</th><th>ពិន្ទុ</th><th>GPA</th></tr></thead>
                        <tbody>
                            <tr><td><span class="badge bg-success">A</span></td><td>≥ 90</td><td>4.0</td></tr>
                            <tr><td><span class="badge bg-primary">B+</span></td><td>80-89</td><td>3.5</td></tr>
                            <tr><td><span class="badge bg-primary">B</span></td><td>70-79</td><td>3.0</td></tr>
                            <tr><td><span class="badge bg-info text-dark">C+</span></td><td>65-69</td><td>2.5</td></tr>
                            <tr><td><span class="badge bg-info text-dark">C</span></td><td>60-64</td><td>2.0</td></tr>
                            <tr><td><span class="badge bg-warning text-dark">D+</span></td><td>55-59</td><td>1.5</td></tr>
                            <tr><td><span class="badge bg-warning text-dark">D</span></td><td>50-54</td><td>1.0</td></tr>
                            <tr><td><span class="badge bg-danger">F</span></td><td>< 50</td><td>0.0</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Results -->
    <div class="col-lg-7">
        <?php if (!empty($students)): ?>
        <!-- Stats -->
        <div class="row g-3 mb-3 fade-in">
            <?php
            $statCards = [
                ['label'=>'សិស្ស','value'=>$stats['count'],'color'=>'primary','icon'=>'bi-people-fill'],
                ['label'=>'ជាមធ្យម','value'=>$stats['avg'],'color'=>'success','icon'=>'bi-graph-up'],
                ['label'=>'ខ្ពស់បំផុត','value'=>$stats['max'],'color'=>'warning','icon'=>'bi-trophy-fill'],
                ['label'=>'ទាបបំផុត','value'=>$stats['min'],'color'=>'danger','icon'=>'bi-arrow-down-circle'],
                ['label'=>'ជាប់','value'=>$stats['pass'],'color'=>'success','icon'=>'bi-check-circle-fill'],
                ['label'=>'ធ្លាក់','value'=>$stats['fail'],'color'=>'danger','icon'=>'bi-x-circle-fill'],
            ];
            foreach ($statCards as $sc):
            ?>
            <div class="col-4 col-sm-2 col-lg-4">
                <div class="card text-center border-<?= $sc['color'] ?> h-100">
                    <div class="card-body py-2 px-1">
                        <i class="bi <?= $sc['icon'] ?> text-<?= $sc['color'] ?> fs-5 d-block mb-1"></i>
                        <div class="fw-bold fs-5"><?= $sc['value'] ?></div>
                        <div class="text-muted" style="font-size:.7rem;"><?= $sc['label'] ?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Student Table -->
        <div class="card shadow fade-in">
            <div class="card-header bg-warning text-dark">
                <i class="bi bi-table me-2"></i><strong>តារាងពិន្ទុ</strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ឈ្មោះ</th>
                                <th class="text-center">ពិន្ទុ</th>
                                <th class="text-center">Grade</th>
                                <th class="text-center">GPA</th>
                                <th>ផ្ដល់យោបល់</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $i => $s): ?>
                            <tr class="<?= $s['grade']==='F' ? 'table-danger' : '' ?>">
                                <td class="text-muted small"><?= $i+1 ?></td>
                                <td class="fw-semibold"><?= htmlspecialchars($s['name']) ?></td>
                                <td class="text-center">
                                    <span class="fw-bold fs-5"><?= $s['score'] ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-<?= $s['color'] ?> fs-6 px-3">
                                        <?= $s['grade'] ?>
                                    </span>
                                </td>
                                <td class="text-center"><?= $s['gpa'] ?></td>
                                <td><small class="text-<?= $s['color'] ?>"><?= $s['remark'] ?></small></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="card border-0 bg-light h-100 d-flex align-items-center justify-content-center">
            <div class="text-center text-muted p-5">
                <i class="bi bi-journal-bookmark fs-1 d-block mb-2 text-warning opacity-50"></i>
                <p>បញ្ចូលឈ្មោះ និងពិន្ទុ ហើយចុចគណនា</p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
