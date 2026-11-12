<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$pageTitle = 'លំហាត់ ១០ — Todo List';

// Handle actions
$action = $_POST['action'] ?? $_GET['action'] ?? '';
$todoId = sanitize($_POST['todo_id'] ?? $_GET['id'] ?? '');

switch ($action) {
    case 'add':
        $title    = sanitize($_POST['title'] ?? '');
        $priority = in_array($_POST['priority'] ?? 'normal', ['low','normal','high']) ? $_POST['priority'] : 'normal';
        if (!empty($title)) {
            addTodo($title, $priority);
        }
        break;
    case 'toggle':
        if ($todoId) toggleTodo($todoId);
        break;
    case 'delete':
        if ($todoId) deleteTodo($todoId);
        break;
    case 'clear_done':
        clearDoneTodos();
        break;
}

$todos = getTodos();
$total = count($todos);
$done  = count(array_filter($todos, fn($t) => $t['done']));
$pct   = $total > 0 ? round(($done / $total) * 100) : 0;

$priorityConfig = [
    'high'   => ['label'=>'បន្ទាន់',   'color'=>'danger'],
    'normal' => ['label'=>'ធម្មតា',    'color'=>'primary'],
    'low'    => ['label'=>'ទាប',       'color'=>'secondary'],
];

include '../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-8">

        <!-- Add Todo Form -->
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <i class="bi bi-check2-square me-2"></i>
                <strong>EX10 — គ្រប់គ្រងបញ្ជីការងារ (Session-based)</strong>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="">
                    <input type="hidden" name="action" value="add">
                    <div class="row g-3">
                        <div class="col-md-7">
                            <label class="form-label fw-bold">
                                <i class="bi bi-pencil-square me-1 text-primary"></i>ការងារថ្មី
                            </label>
                            <input type="text" name="title" class="form-control"
                                   placeholder="ឧ. ស្រួលការងារ, ប្រជុំ, ការសិក្សា..."
                                   required autofocus>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">
                                <i class="bi bi-flag me-1 text-warning"></i>អទិភាព
                            </label>
                            <select name="priority" class="form-select">
                                <option value="normal">ធម្មតា</option>
                                <option value="high">🔴 បន្ទាន់</option>
                                <option value="low">⚪ ទាប</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-plus-lg"></i> បន្ថែម
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Progress -->
        <?php if ($total > 0): ?>
        <div class="card mb-3 border-0 shadow-sm fade-in">
            <div class="card-body py-3 px-4">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="fw-bold small">
                        <i class="bi bi-graph-up me-1 text-success"></i>
                        ដំណើរការ: <?= $done ?>/<?= $total ?> ការងារ
                    </span>
                    <span class="fw-bold text-success"><?= $pct ?>%</span>
                </div>
                <div class="progress" style="height:10px;">
                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated"
                         style="width:<?= $pct ?>%"></div>
                </div>
                <div class="d-flex gap-2 mt-2 small text-muted">
                    <span><i class="bi bi-circle me-1 text-primary"></i><?= $total - $done ?> នៅសល់</span>
                    <span><i class="bi bi-check-circle-fill me-1 text-success"></i><?= $done ?> បានធ្វើ</span>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Todo List -->
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center bg-dark text-white">
                <span><i class="bi bi-list-task me-2"></i><strong>បញ្ជីការងារ</strong>
                    <span class="badge bg-primary ms-2"><?= $total ?></span>
                </span>
                <?php if ($done > 0): ?>
                <form method="POST" action="" class="d-inline">
                    <input type="hidden" name="action" value="clear_done">
                    <button type="submit" class="btn btn-sm btn-outline-warning"
                            data-confirm="លុបការងារដែលបានធ្វើចេញ?">
                        <i class="bi bi-trash3 me-1"></i>លុបដែលបានធ្វើ
                    </button>
                </form>
                <?php endif; ?>
            </div>
            <div class="card-body p-3">
                <?php if (empty($todos)): ?>
                <div class="text-center text-muted py-5">
                    <i class="bi bi-clipboard2-check fs-1 d-block mb-2 opacity-50"></i>
                    <p>មិនទាន់មានការងារ — បន្ថែមមួយ!</p>
                </div>
                <?php else: ?>
                <?php
                // Sort: high priority first, then undone, then done
                usort($todos, function($a,$b) {
                    $pOrder = ['high'=>0,'normal'=>1,'low'=>2];
                    if ($a['done'] !== $b['done']) return $a['done'] <=> $b['done'];
                    return ($pOrder[$a['priority']] ?? 1) <=> ($pOrder[$b['priority']] ?? 1);
                });
                ?>
                <?php foreach ($todos as $todo): ?>
                <div class="todo-item <?= $todo['done'] ? 'done' : '' ?>">
                    <!-- Toggle -->
                    <form method="POST" action="" class="d-inline">
                        <input type="hidden" name="action" value="toggle">
                        <input type="hidden" name="todo_id" value="<?= $todo['id'] ?>">
                        <button type="submit" class="btn btn-sm border-0 p-0 me-1"
                                title="<?= $todo['done'] ? 'ยกเลิก' : 'เสร็จแล้ว' ?>">
                            <i class="bi <?= $todo['done'] ? 'bi-check-circle-fill text-success' : 'bi-circle text-muted' ?> fs-5"></i>
                        </button>
                    </form>

                    <!-- Title -->
                    <div class="flex-grow-1">
                        <span class="todo-title"><?= htmlspecialchars($todo['title']) ?></span>
                        <div class="mt-1 d-flex gap-2 flex-wrap">
                            <?php $pc = $priorityConfig[$todo['priority']] ?? $priorityConfig['normal']; ?>
                            <span class="priority-badge bg-<?= $pc['color'] ?> text-white">
                                <?= $pc['label'] ?>
                            </span>
                            <span class="text-muted" style="font-size:.72rem;">
                                <i class="bi bi-clock me-1"></i><?= $todo['created_at'] ?>
                            </span>
                        </div>
                    </div>

                    <!-- Delete -->
                    <form method="POST" action="" class="d-inline">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="todo_id" value="<?= $todo['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-outline-danger border-0"
                                data-confirm="លុប '<?= htmlspecialchars($todo['title']) ?>'?">
                            <i class="bi bi-trash3"></i>
                        </button>
                    </form>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="card mt-4 border-0 bg-light">
            <div class="card-body small">
                <h6 class="fw-bold"><i class="bi bi-code-slash me-2 text-primary"></i>Functions ប្រើ (Session)</h6>
                <ul class="mb-0">
                    <li><code>addTodo(title, priority)</code> — បន្ថែម Todo ក្នុង <code>$_SESSION</code></li>
                    <li><code>toggleTodo(id)</code> — ប្ដូរ done/undone</li>
                    <li><code>deleteTodo(id)</code> — លុប Todo</li>
                    <li><code>clearDoneTodos()</code> — លុបដែលបានធ្វើទាំងអស់</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
