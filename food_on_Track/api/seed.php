<?php
/**
 * seed.php
 * One-time helper: creates a default test account using a REAL
 * password_hash() so it can actually log in.
 *
 *   Test login ->  phone: 01712345678  password: 123
 *
 * Just open this file in your browser once:
 *     http://localhost/.../api/seed.php
 * Then DELETE this file (or leave it — it is safe to re-run).
 */
require __DIR__ . '/db.php';

$name     = 'রাকিব হাসান';
$phone    = '01712345678';
$password = '123';

$pdo = db();

// Already exists? Update the password so it is guaranteed valid.
$stmt = $pdo->prepare('SELECT id FROM users WHERE phone = ? LIMIT 1');
$stmt->execute([$phone]);
$existing = $stmt->fetch();

$hash = password_hash($password, PASSWORD_DEFAULT);

if ($existing) {
    $upd = $pdo->prepare('UPDATE users SET name = ?, password = ? WHERE id = ?');
    $upd->execute([$name, $hash, $existing['id']]);
    json_ok(['message' => 'Test account already existed — password reset to "123".']);

} else {
    $ins = $pdo->prepare('INSERT INTO users (name, phone, password) VALUES (?, ?, ?)');
    $ins->execute([$name, $phone, $hash]);
    json_ok(['message' => 'Test account created. Login with 01712345678 / 123']);
}
