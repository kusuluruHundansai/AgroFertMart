<?php
require 'php/razorpay-config.php';
require 'php/db.php';

$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Log webhook (optional for debugging)
file_put_contents('logs/webhook_log.txt', $input, FILE_APPEND);

if (!empty($data['event']) && $data['event'] == 'payment.captured') {
    $paymentId = $data['payload']['payment']['entity']['id'];
    $amount = $data['payload']['payment']['entity']['amount'] / 100;

    // Save or update order status in DB (you could map using payment ID or session-based logic)
}
?>
