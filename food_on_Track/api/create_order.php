<?php
/**
 * create_order.php
 * Save a new order (+ its food items) for a logged-in user.
 *
 * Input (JSON POST):
 *   user_id, items: [{ food_id, title, price, qty }, ...],
 *   station, pnr, coach, seat, payment_method
 *
 * Output (JSON): { success, message, order: { order_code, total } }
 */
require __DIR__ . '/db.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    json_error('Only POST requests are allowed.', 405);
}

$data    = request_data();
$userId  = (int) ($data['user_id'] ?? 0);
$items   = $data['items'] ?? [];
$station = field($data, 'station');
$pnr     = field($data, 'pnr');
$coach   = field($data, 'coach');
$seat    = field($data, 'seat');
$payment = field($data, 'payment_method');
$payment = $payment !== '' ? $payment : 'cod';

// ---- Validation ----
if ($userId <= 0) {
    json_error('অর্ডার করতে আগে লগইন করুন।', 401);
}
if (!is_array($items) || count($items) === 0) {
    json_error('আপনার কার্টে কোনো খাবার নেই!');
}
if ($station === '') {
    json_error('ডেলিভারি স্টেশন সিলেক্ট করুন।');
}

$pdo = db();

// ---- Confirm the user exists ----
$check = $pdo->prepare('SELECT id FROM users WHERE id = ? LIMIT 1');
$check->execute([$userId]);
if (!$check->fetch()) {
    json_error('ব্যবহারকারী খুঁজে পাওয়া যায়নি। আবার লগইন করুন।', 401);
}

// ---- Calculate total & normalise items ----
$total = 0.0;
$cleanItems = [];
foreach ($items as $item) {
    $title = isset($item['title']) ? trim((string) $item['title']) : '';
    $price = isset($item['price']) ? (float) $item['price'] : 0.0;
    $qty   = isset($item['qty']) ? (int) $item['qty'] : 0;
    $foodId = isset($item['food_id']) ? (int) $item['food_id'] : 0;

    if ($title === '' || $qty <= 0) {
        continue;
    }
    $total += $price * $qty;
    $cleanItems[] = [
        'food_id' => $foodId,
        'title'   => $title,
        'price'   => $price,
        'qty'     => $qty,
    ];
}

if (count($cleanItems) === 0) {
    json_error('অর্ডারের খাবারগুলো সঠিক নয়।');
}

// Generate a unique order code, e.g. BD-482915.
// random_int($min, $max) needs $min <= $max, so use the full range
// and retry on the (very rare) chance of a collision.
$orderCode = '';
for ($attempt = 0; $attempt < 5; $attempt++) {
    $candidate = 'BD-' . str_pad((string) random_int(0, 999), 6, '0', STR_PAD_LEFT);
    $dupe = $pdo->prepare('SELECT id FROM orders WHERE order_code = ? LIMIT 1');
    $dupe->execute([$candidate]);
    if (!$dupe->fetch()) {
        $orderCode = $candidate;
        break;
    }
}
if ($orderCode === '') {
    json_error('অর্ডার আইডি তৈরি করা যায়নি। আবার চেষ্টা করুন।', 500);
}

try {
    $pdo->beginTransaction();

    $insOrder = $pdo->prepare(
        'INSERT INTO orders
            (order_code, user_id, pnr, coach, seat, station, payment_method, total_amount, status)
         VALUES (:order_code, :user_id, :pnr, :coach, :seat, :station, :payment_method, :total_amount, :status)'
    );
    $insOrder->execute([
        ':order_code'     => $orderCode,
        ':user_id'        => $userId,
        ':pnr'            => $pnr,
        ':coach'          => $coach,
        ':seat'           => $seat,
        ':station'        => $station,
        ':payment_method' => $payment,
        ':total_amount'   => $total,
        ':status'         => 'On the way',
    ]);
    $orderId = (int) $pdo->lastInsertId();

    $insItem = $pdo->prepare(
        'INSERT INTO order_items (order_id, food_id, title, price, qty) VALUES (:order_id, :food_id, :title, :price, :qty)'
    );
    foreach ($cleanItems as $ci) {
        $insItem->execute([
            ':order_id' => $orderId,
            ':food_id'  => $ci['food_id'],
            ':title'    => $ci['title'],
            ':price'    => $ci['price'],
            ':qty'      => $ci['qty'],
        ]);
    }

    $pdo->commit();
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    json_error('অর্ডার সেভ করা যায়নি: ' . $e->getMessage(), 500);
}

json_ok([
    'message' => 'আপনার অর্ডার সফলভাবে গ্রহণ করা হয়েছে!',
    'order'   => [
        'order_code' => $orderCode,
        'total'      => $total,
        'station'    => $station,
    ],
]);
