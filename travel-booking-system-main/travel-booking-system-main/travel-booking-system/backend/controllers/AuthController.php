<?php
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../helpers/validation.php';

class AuthController {
    private $user;

    public function __construct($pdo) {
        $this->user = new User($pdo);
    }

    public function login($email, $password) {
        if (!validateEmail($email)) return ['error' => 'Invalid email address.'];
        $user = $this->user->findByEmail($email);
        if (!$user || !password_verify($password, $user['password'])) {
            return ['error' => 'Invalid email or password.'];
        }
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        return ['success' => true, 'role' => $user['role']];
    }

    public function register($name, $email, $password, $confirm, $phone = null) {
        $errors = [];
        if (empty($name)) $errors[] = 'Name is required.';
        if (!validateEmail($email)) $errors[] = 'Invalid email address.';
        if (!validatePassword($password)) $errors[] = 'Password must be at least 8 characters.';
        if ($password !== $confirm) $errors[] = 'Passwords do not match.';
        if (!empty($errors)) return ['errors' => $errors];

        if ($this->user->findByEmail($email)) return ['error' => 'Email already registered.'];

        $id = $this->user->create($name, $email, $password, $phone);
        $_SESSION['user_id'] = $id;
        $_SESSION['user_name'] = $name;
        $_SESSION['role'] = 'user';
        return ['success' => true];
    }

    public function logout() {
        session_destroy();
    }
}
