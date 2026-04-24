<?php
/**
 * Auth Request Validator
 * Validates login and registration input
 */

require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../helpers/validation.php';

function validateLoginRequest(array $data): array {
    $errors = [];
    if (empty($data['email']) || !validateEmail($data['email'])) {
        $errors[] = 'Valid email address is required.';
    }
    if (empty($data['password'])) {
        $errors[] = 'Password is required.';
    }
    return ['errors' => $errors, 'data' => [
        'email'    => sanitize($data['email'] ?? ''),
        'password' => $data['password'] ?? '',
    ]];
}

function validateRegisterRequest(array $data): array {
    $errors = [];
    if (empty($data['name']) || strlen(trim($data['name'])) < 2) {
        $errors[] = 'Name must be at least 2 characters.';
    }
    if (empty($data['email']) || !validateEmail($data['email'])) {
        $errors[] = 'Valid email address is required.';
    }
    if (empty($data['password']) || !validatePassword($data['password'])) {
        $errors[] = 'Password must be at least 8 characters.';
    }
    if (($data['password'] ?? '') !== ($data['confirm_password'] ?? '')) {
        $errors[] = 'Passwords do not match.';
    }
    return ['errors' => $errors, 'data' => [
        'name'    => sanitize($data['name'] ?? ''),
        'email'   => sanitize($data['email'] ?? ''),
        'password'=> $data['password'] ?? '',
        'phone'   => sanitize($data['phone'] ?? ''),
    ]];
}
