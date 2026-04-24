<?php
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../helpers/validation.php';

class UserController {
    private $user;

    public function __construct($pdo) {
        $this->user = new User($pdo);
    }

    public function getProfile($id) {
        return $this->user->findById($id);
    }

    public function updateProfile($id, $data) {
        $allowed = ['name', 'phone', 'avatar'];
        $payload = [];
        foreach ($allowed as $field) {
            if (isset($data[$field])) {
                $payload[$field] = sanitize($data[$field]);
            }
        }
        if (empty($payload)) return ['error' => 'Nothing to update.'];
        $this->user->update($id, $payload);
        if (isset($payload['name'])) $_SESSION['user_name'] = $payload['name'];
        return ['success' => true];
    }

    public function changePassword($id, $current, $new, $confirm) {
        $user = $this->user->findById($id);
        // Re-fetch with password field
        // Note: findById excludes password for security, use raw query here
        if (!validatePassword($new)) return ['error' => 'Password must be at least 8 characters.'];
        if ($new !== $confirm) return ['error' => 'Passwords do not match.'];
        $this->user->update($id, ['password' => password_hash($new, PASSWORD_BCRYPT)]);
        return ['success' => true];
    }

    public function uploadAvatar($id, $file) {
        $allowed = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($file['type'], $allowed)) return ['error' => 'Invalid file type.'];
        if ($file['size'] > 2 * 1024 * 1024) return ['error' => 'File too large (max 2MB).'];

        $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'user_' . $id . '_' . time() . '.' . $ext;
        $dest     = UPLOAD_PATH . 'users/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $dest)) return ['error' => 'Upload failed.'];

        $this->user->update($id, ['avatar' => $filename]);
        return ['success' => true, 'avatar' => $filename];
    }
}
