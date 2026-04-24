<?php
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../backend/middleware/admin.php';
require_once __DIR__ . '/../../backend/helpers/functions.php';
require_once __DIR__ . '/../../backend/controllers/AdminController.php';

$admin = new AdminController($pdo);

if (isset($_GET['delete']) && intval($_GET['delete']) !== $_SESSION['user_id']) {
    $admin->user->delete(intval($_GET['delete']));
    redirect(APP_URL . '/pages/admin/users.php');
}

$users = $admin->user->getAll(100, 0);
$pageTitle = 'Manage Users - Admin';
require_once __DIR__ . '/../../includes/header.php';
?>
<div class="dashboard-layout">
    <?php require_once __DIR__ . '/../../includes/admin-sidebar.php'; ?>
    <main class="dashboard-main">
        <h1 style="font-size: 1.8rem; font-weight: 800; margin-bottom: 32px;">Manage Users</h1>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr><th>#</th><th>Name</th><th>Email</th><th>Role</th><th>Joined</th><th>Action</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= $u['id'] ?></td>
                        <td style="font-weight: 600;"><?= htmlspecialchars($u['name']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><span class="badge badge-<?= $u['role'] === 'admin' ? 'danger' : 'info' ?>"><?= ucfirst($u['role']) ?></span></td>
                        <td><?= formatDate($u['created_at']) ?></td>
                        <td>
                            <?php if ($u['id'] !== $_SESSION['user_id']): ?>
                            <a href="?delete=<?= $u['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete user?')">Delete</a>
                            <?php else: ?>
                            <span style="color: var(--text-muted); font-size: 0.8rem;">You</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
