<?php
/**
 * contact.php
 * Save a message from the "Contact Us" form.
 *
 * Input (JSON or form POST): name, contact, subject, message
 * Output (JSON): { success, message }
 */
require __DIR__ . '/db.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    json_error('Only POST requests are allowed.', 405);
}

$data    = request_data();
$name    = field($data, 'name');
$contact = field($data, 'contact');
$subject = field($data, 'subject');
$message = field($data, 'message');

if ($name === '' || $contact === '' || $subject === '' || $message === '') {
    json_error('সবগুলো ফিল্ড পূরণ করুন।');
}

$pdo = db();

$stmt = $pdo->prepare(
        'INSERT INTO contact_messages (name, contact, subject, message)
         VALUES (:name, :contact, :subject, :message)'
    );
    $stmt->execute([
        ':name'    => $name,
        ':contact' => $contact,
        ':subject' => $subject,
        ':message' => $message,
    ]);

json_ok([
    'message' => 'ধন্যবাদ! আপনার বার্তাটি সফলভাবে আমাদের কাছে পৌঁছেছে। দ্রুতই আপনার সাথে যোগাযোগ করা হবে।',
]);
