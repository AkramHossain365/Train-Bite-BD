<?php
/**
 * migrate.php
 * One-time helper: adds the newer passenger-profile columns to an
 * EXISTING "users" table (safe to re-run — it only adds what is missing).
 *
 * Run once by opening this file in your browser:
 *     http://localhost/food_on_Track/api/migrate.php
 *
 * You only need this if your database was created BEFORE these columns
 * existed. A fresh import of api/database.sql already has them.
 */
require __DIR__ . '/db.php';

$pdo = db();

// Column name => full column definition.
$columns = [
    'email'           => "VARCHAR(150) DEFAULT NULL AFTER phone",
    'gender'          => "VARCHAR(20)  DEFAULT NULL AFTER email",
    'age'             => "INT          DEFAULT NULL AFTER gender",
    'nid'             => "VARCHAR(30)  DEFAULT NULL AFTER age",
    'address'         => "VARCHAR(255) DEFAULT NULL AFTER nid",
    'default_station' => "VARCHAR(150) DEFAULT NULL AFTER address",
];

// Which columns already exist?
$existing = [];
$stmt = $pdo->query('SHOW COLUMNS FROM users');
foreach ($stmt->fetchAll() as $col) {
    $existing[] = $col['Field'];
}

$added = [];
foreach ($columns as $name => $definition) {
    if (!in_array($name, $existing, true)) {
        $pdo->exec("ALTER TABLE users ADD COLUMN `$name` $definition");
        $added[] = $name;
    }
}

json_ok([
    'message' => count($added)
        ? 'নতুন কলাম যোগ করা হয়েছে: ' . implode(', ', $added)
        : 'সব কলাম আগেই আছে — কিছু করার দরকার নেই।',
    'added'   => $added,
]);
