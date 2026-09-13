<?php
/**
 * login.php
 * Authenticate a passenger.
 *
 * Input  (JSON or form POST): phone, password
 * Output (JSON): { success, message, user: { id, name, phone } }
 */
require __DIR__ . '/db.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    json_error('Only POST requests are allowed.', 405);
}

$data     = request_data();
$phone    = field($data, 'phone');
$password = field($data, 'password');

if ($phone === '' || $password === '') {
    json_error('মোবাইল নম্বর ও পাসওয়ার্ড দিন।');
}

$pdo = db();

$stmt = $pdo->prepare(
    'SELECT id, name, phone, email, gender, age, nid, address, default_station, password
     FROM users WHERE phone = ? LIMIT 1'
);
$stmt->execute([$phone]);
$user = $stmt->fetch();

if (!$user) {
    json_error('এই মোবাইল নম্বর দিয়ে কোনো অ্যাকাউন্ট পাওয়া যায়নি! আগে রেজিস্ট্রেশন করুন।', 404);
}

if (!password_verify($password, $user['password'])) {
    json_error('ভুল পাসওয়ার্ড! সঠিক পাসওয়ার্ড দিয়ে আবার চেষ্টা করুন।', 401);
}

// Never send the password hash back to the client.
unset($user['password']);

json_ok([
    'message' => 'লগইন সফল হয়েছে।',
    'user'    => [
        'id'              => (int) $user['id'],
        'name'            => $user['name'],
        'phone'           => $user['phone'],
        'email'           => $user['email'],
        'gender'          => $user['gender'],
        'age'             => $user['age'] !== null ? (int) $user['age'] : null,
        'nid'             => $user['nid'],
        'address'         => $user['address'],
        'default_station' => $user['default_station'],
    ],
]);
