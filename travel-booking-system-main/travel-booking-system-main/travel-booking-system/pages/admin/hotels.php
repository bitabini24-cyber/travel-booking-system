<?php
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../backend/middleware/admin.php';
require_once __DIR__ . '/../../backend/helpers/functions.php';
require_once __DIR__ . '/../../backend/controllers/AdminController.php';

$admin = new AdminController($pdo);
$msg = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $editId = intval($_POST['edit_id'] ?? 0);
    $result = $admin->saveHotel($_POST, $editId ?: null);
    $msg = $result['success'] ? 'success:Hotel saved successfully!' : 'error:' . implode(', ', $result['errors'] ?? ['Error']);
}

// Handle delete
if (isset($_GET['delete'])) {
    $admin->hotel->delete(intval($_GET['delete']));
    redirect(APP_URL . '/pages/admin/hotels.php?msg=deleted');
}

$hotels = $admin->hotel->getAll([], 100, 0);
$editHotel = null;
if (isset($_GET['edit'])) {
    $editHotel = $admin->hotel->findById(intval($_GET['edit']));
}

$pageTitle = 'Manage Hotels - Admin';
require_once __DIR__ . '/../../includes/header.php';
?>
<div class="dashboard-layout">
    <?php require_once __DIR__ . '/../../includes/admin-sidebar.php'; ?>
    <main class="dashboard-main">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; flex-wrap: wrap; gap: 16px;">
            <h1 style="font-size: 1.8rem; font-weight: 800;">Manage Hotels</h1>
            <button class="btn btn-primary" onclick="document.getElementById('hotelModal').classList.add('active')">+ Add Hotel</button>
        </div>

        <?php if ($msg): ?>
            <?php [$type, $text] = explode(':', $msg, 2); ?>
            <div class="alert alert-<?= $type === 'success' ? 'success' : 'error' ?>"><?= htmlspecialchars($text) ?></div>
        <?php endif; ?>
        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
            <div class="alert alert-success">Hotel deleted successfully.</div>
        <?php endif; ?>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr><th>Image</th><th>Name</th><th>City</th><th>Price</th><th>Rating</th><th>Stars</th><th>Featured</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($hotels as $h): ?>
                    <tr>
                        <td><img src="<?= htmlspecialchars($h['image']) ?>" style="width: 60px; height: 45px; border-radius: 6px; object-fit: cover;" alt=""></td>
                        <td style="font-weight: 600;"><?= htmlspecialchars($h['name']) ?></td>
                        <td><?= htmlspecialchars($h['city']) ?>, <?= htmlspecialchars($h['country']) ?></td>
                        <td><?= formatPrice($h['price']) ?></td>
                        <td><?= number_format($h['rating'], 1) ?> ⭐</td>
                        <td><?= $h['stars'] ?>★</td>
                        <td><?= $h['is_featured'] ? '<span class="badge badge-success">Yes</span>' : '<span class="badge badge-info">No</span>' ?></td>
                        <td>
                            <a href="?edit=<?= $h['id'] ?>" class="btn btn-sm" style="background: var(--bg); color: var(--text);">Edit</a>
                            <a href="?delete=<?= $h['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this hotel?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<!-- HOTEL MODAL -->
<div class="modal-overlay <?= ($editHotel || $_SERVER['REQUEST_METHOD'] === 'POST') ? 'active' : '' ?>" id="hotelModal">
    <div class="modal" style="max-width: 700px;">
        <div class="modal-header">
            <h3 style="font-weight: 700;"><?= $editHotel ? 'Edit Hotel' : 'Add New Hotel' ?></h3>
            <button class="modal-close" onclick="document.getElementById('hotelModal').classList.remove('active')">✕</button>
        </div>
        <form method="POST">
            <input type="hidden" name="edit_id" value="<?= $editHotel['id'] ?? 0 ?>">
            <div class="form-row">
                <div class="form-group">
                    <label>Hotel Name *</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($editHotel['name'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label>Stars *</label>
                    <select name="stars" required>
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <option value="<?= $i ?>" <?= ($editHotel['stars'] ?? 3) == $i ? 'selected' : '' ?>><?= $i ?> Stars</option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label>Location (Full Address) *</label>
                <input type="text" name="location" value="<?= htmlspecialchars($editHotel['location'] ?? '') ?>" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>City *</label>
                    <input type="text" name="city" value="<?= htmlspecialchars($editHotel['city'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label>Country *</label>
                    <input type="text" name="country" value="<?= htmlspecialchars($editHotel['country'] ?? '') ?>" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Latitude</label>
                    <input type="number" name="lat" step="any" value="<?= $editHotel['lat'] ?? '' ?>" placeholder="e.g. 48.8566">
                </div>
                <div class="form-group">
                    <label>Longitude</label>
                    <input type="number" name="lng" step="any" value="<?= $editHotel['lng'] ?? '' ?>" placeholder="e.g. 2.3522">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Price per Night ($) *</label>
                    <input type="number" name="price" step="0.01" value="<?= $editHotel['price'] ?? '' ?>" required>
                </div>
                <div class="form-group">
                    <label>Total Rooms</label>
                    <input type="number" name="total_rooms" value="<?= $editHotel['total_rooms'] ?? 10 ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Image URL</label>
                <input type="url" name="image" value="<?= htmlspecialchars($editHotel['image'] ?? '') ?>" placeholder="https://images.unsplash.com/...">
            </div>
            <div class="form-group">
                <label>Amenities (comma-separated)</label>
                <input type="text" name="amenities" value="<?= htmlspecialchars($editHotel['amenities'] ?? '') ?>" placeholder="WiFi,Pool,Spa,Restaurant">
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="3"><?= htmlspecialchars($editHotel['description'] ?? '') ?></textarea>
            </div>
            <div class="form-group" style="display: flex; align-items: center; gap: 10px;">
                <input type="checkbox" name="is_featured" id="isFeatured" <?= ($editHotel['is_featured'] ?? 0) ? 'checked' : '' ?> style="width: auto;">
                <label for="isFeatured" style="margin: 0;">Featured on homepage</label>
            </div>
            <button type="submit" class="btn btn-primary btn-full">Save Hotel</button>
        </form>
    </div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
