<?php
/**
 * Auth System Tests
 * Run: php tests/auth-test.php
 */

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../backend/helpers/functions.php';
require_once __DIR__ . '/../backend/helpers/validation.php';
require_once __DIR__ . '/../backend/models/User.php';
require_once __DIR__ . '/../backend/controllers/AuthController.php';

$passed = 0;
$failed = 0;

function assert_test(string $name, bool $condition): void {
    global $passed, $failed;
    if ($condition) {
        echo "\033[32m✓ PASS\033[0m $name\n";
        $passed++;
    } else {
        echo "\033[31m✗ FAIL\033[0m $name\n";
        $failed++;
    }
}

echo "\n=== Auth Tests ===\n\n";

// --- Validation Tests ---
assert_test('validateEmail: valid email',    validateEmail('test@example.com'));
assert_test('validateEmail: invalid email',  !validateEmail('not-an-email'));
assert_test('validatePassword: 8+ chars',    validatePassword('password123'));
assert_test('validatePassword: too short',   !validatePassword('abc'));

// --- Password Hashing ---
$hash = password_hash('testpass', PASSWORD_BCRYPT);
assert_test('password_hash creates hash',    strlen($hash) > 20);
assert_test('password_verify correct pass',  password_verify('testpass', $hash));
assert_test('password_verify wrong pass',    !password_verify('wrongpass', $hash));

// --- Sanitize ---
assert_test('sanitize strips HTML tags',     sanitize('<script>alert(1)</script>') === 'alert(1)');
assert_test('sanitize trims whitespace',     sanitize('  hello  ') === 'hello');

// --- AuthController ---
$ctrl = new AuthController($pdo);

// Test login with wrong credentials
$result = $ctrl->login('nonexistent@test.com', 'wrongpass');
assert_test('Login fails with bad credentials', isset($result['error']));

// Test register with mismatched passwords
$result = $ctrl->register('Test User', 'test_' . time() . '@test.com', 'pass1234', 'different', null);
assert_test('Register fails with mismatched passwords', isset($result['errors']));

// Test register with invalid email
$result = $ctrl->register('Test User', 'not-valid', 'pass1234', 'pass1234', null);
assert_test('Register fails with invalid email', isset($result['errors']));

// --- Summary ---
echo "\n--- Results: $passed passed, $failed failed ---\n\n";
exit($failed > 0 ? 1 : 0);
