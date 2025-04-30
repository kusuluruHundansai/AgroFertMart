<?php
require_once "db.php";

// Check if the payment was successful
if (isset($_POST['payment_id']) && isset($_POST['order_id'])) {
    $paymentId = $_POST['payment_id'];
    $orderId = $_POST['order_id'];
    
    // Verify payment via Razorpay API
    $apiKey = 'your_razorpay_key_id';
    $apiSecret = 'your_razorpay_key_secret';
    $apiUrl = "https://api.razorpay.com/v1/payments/$paymentId";

    // Call Razorpay API to verify the payment
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERPWD, "$apiKey:$apiSecret");
    $response = curl_exec($ch);
    curl_close($ch);
    
    $paymentDetails = json_decode($response, true);

    // Check payment status
    if ($paymentDetails['status'] == 'captured') {
        // Payment is successful
        // Update order status in the database
        $stmt = $pdo->prepare("UPDATE orders SET payment_status = 'paid' WHERE id = ?");
        $stmt->execute([$orderId]);

        // Send success message or redirect
        echo "Payment successful! Your order is confirmed.";
    } else {
        // Payment failed
        echo "Payment failed. Please try again.";
    }
} else {
    echo "Invalid request.";
}
?>
