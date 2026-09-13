<?php
/**
 * register.php
 * Create a new passenger account.
 *
 * Input  (JSON or form POST):
 *   name, phone, email, gender, age, nid, address, default_station, password
 * Output (JSON): { success, message, user: {...} }
 */
require __DIR__ . '/db.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    json_error('Only POST requests are allowed.', 405);
}

$data     = request_data();
$name     = field($data, 'name');
$phone    = field($data, 'phone');
$email    = field($data, 'email');
$gender   = field($data, 'gender');
$ageRaw   = field($data, 'age');
$nid      = field($data, 'nid');
$address  = field($data, 'address');
$station  = field($data, 'default_station');
$password = field($data, 'password');

$age = $ageRaw === '' ? null : (int) $ageRaw;

// ---- Validation ----
if ($name === '' || $phone === '' || $password === '') {
    json_error('নাম, মোবাইল নম্বর ও পাসওয়ার্ড — সবগুলো পূরণ করুন।');
}

if (!preg_match('/^01[3-9]\d{8}$/', $phone)) {
    json_error('সঠিক ১ ডিজিটের মোবাইল নম্বর দিন (যেমন: 01XXXXXXXXX)।');
}

if (mb_strlen($name) < 3) {
    json_error('আপনার পূর্ণ নাম লিখুন (কমপক্ষে ৩ অক্ষর)।');
}

if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    json_error('সঠিক ইমেইল ঠিকানা দিন (যেমন: name@example.com)।');
}

if ($gender !== '' && !in_array($gender, ['male', 'female', 'other'], true)) {
    json_error('লিঙ্গ নির্বাচন সঠিক নয়।');
}

if ($age !== null && ($age < 10 || $age > 110)) {
    json_error('বয়স ১০ থেকে ১১০ বছরের মধ্যে হতে হবে।');
}

if ($nid !== '' && !preg_match('/^\d{10,17}$/', $nid)) {
    json_error('জাতীয় আইডি (NID) ১০ থেকে ১৭ ডিজিটের হতে হবে।');
}

if (strlen($password) < 6) {
    json_error('পাসওয়ার্ড কমপক্ষে ৬ অক্ষরের হতে হবে।');
}

$pdo = db();

// ---- Phone already used? ----
$stmt = $pdo->prepare('SELECT id FROM users WHERE phone = ? LIMIT 1');
$stmt->execute([$phone]);
if ($stmt->fetch()) {
    json_error('এই মোবাইল নম্বর দিয়ে আগেই অ্যাকাউন্ট খোলা হয়েছে! অনুগ্রহ করে লগইন করুন।');
}

// ---- Insert new user (password hashed) ----
$hash = password_hash($password, PASSWORD_DEFAULT);
$insert = $pdo->prepare(
    'INSERT INTO users
        (name, phone, email, gender, age, nid, address, default_station, password)
     VALUES
        (:name, :phone, :email, :gender, :age, :nid, :address, :station, :password)'
);
$insert->execute([
    ':name'     => $name,
    ':phone'    => $phone,
    ':email'    => $email !== '' ? $email : null,
    ':gender'   => $gender !== '' ? $gender : null,
    ':age'      => $age,
    ':nid'      => $nid !== '' ? $nid : null,
    ':address'  => $address !== '' ? $address : null,
    ':station'  => $station !== '' ? $station : null,
    ':password' => $hash,
]);

$id = (int) $pdo->lastInsertId();

json_ok([
    'message' => 'আপনার রেজিস্ট্রেশন সফল হয়েছে! এখন পাসওয়ার্ড দিয়ে লগইন করুন।',
    'user'    => [
        'id'              => $id,
        'name'            => $name,
        'phone'           => $phone,
        'email'           => $email,
        'gender'          => $gender,
        'age'             => $age,
        'default_station' => $station,
    ],
]);
