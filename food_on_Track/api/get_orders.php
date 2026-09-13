<?php
/**
 * get_orders.php
 * Return the order history for a user (newest first).
 *
 * Input: user_id (GET query string  OR  JSON/form POST)
 * Output (JSON): { success, orders: [ { id, order_code, status, total, station,
 *                                        items (string), created_at }, ... ] }
 */
require __DIR__ . '/db.php';

// Accept user_id from GET (preferred) or POST body.
$userId = (int) ($_GET['user_id'] ?? 0);
if ($userId <= 0) {
    $body   = request_data();
    $userId = (int) ($body['user_id'] ?? 0);
}

if ($userId <= 0) {
    json_error('user_id প্রয়োজন।', 400);
}

$pdo = db();

// ---- Fetch orders for this user ----
$stmt = $pdo->prepare(
    'SELECT id, order_code, status, total_amount, station, payment_method, created_at
     FROM orders
     WHERE user_id = ?
     ORDER BY id DESC'
);
$stmt->execute([$userId]);
$orders = $stmt->fetchAll();

if (!$orders) {
    json_ok(['orders' => []]);
}

// ---- Fetch all line items for those orders in one query ----
$orderIds = array_column($orders, 'id');
$in       = implode(',', array_fill(0, count($orderIds), '?'));

$itemStmt = $pdo->prepare(
    "SELECT order_id, title, qty FROM order_items WHERE order_id IN ($in) ORDER BY id ASC"
);
$itemStmt->execute($orderIds);
$rows = $itemStmt->fetchAll();

// Group item titles per order, e.g.  "বিরিয়ানি (2টি), বোরহানি (1টি)"
$itemsByOrder = [];
foreach ($rows as $r) {
    $itemsByOrder[$r['order_id']][] = $r['title'] . ' (' . $r['qty'] . 'টি)';
}

$result = [];
foreach ($orders as $o) {
    $result[] = [
        'id'             => (int) $o['id'],
        'order_code'     => $o['order_code'],
        'status'         => $o['status'],
        'total'          => (float) $o['total_amount'],
        'station'        => $o['station'],
        'payment_method' => $o['payment_method'],
        'items'          => isset($itemsByOrder[$o['id']]) ? implode(', ', $itemsByOrder[$o['id']]) : '',
        'created_at'     => $o['created_at'],
    ];
}

json_ok(['orders' => $result]);
