<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "db.php";

// Check if the user is logged in and an order ID is provided
if (!isset($_SESSION['user_id']) || !isset($_GET['order_id'])) {
    header("Location: login.php");
    exit();
}

$orderId = intval($_GET['order_id']);
$userId = $_SESSION['user_id'];

// Fetch order details from the database
$stmt = $pdo->prepare("SELECT total FROM orders WHERE id = ? AND user_id = ?");
$stmt->execute([$orderId, $userId]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    die("Invalid order.");
}

$totalAmount = $order['total'] * 100; // Razorpay accepts amount in paise (1 INR = 100 paise)

// Razorpay API Keys (use your test keys here)
$keyId = 'rzp_test_m1MdvqdjKKTF8Y';  // Replace with your actual Razorpay key ID
$keySecret = '7K1lyvOTtB3bIGTyOdXkAK43'; // Replace with your actual Razorpay key secret

// Order creation via Razorpay API
$data = [
    "amount" => $totalAmount, // Amount in paise
    "currency" => "INR",
    "receipt" => "order_receipt_" . time(), // Unique receipt number
    "payment_capture" => 1 // Automatically capture the payment
];

// Initialize CURL for Razorpay API
$ch = curl_init("https://api.razorpay.com/v1/orders");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_USERPWD, "$keyId:$keySecret");
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    // Handle CURL error
    echo "Error creating Razorpay order: " . $error;
    exit();
} else {
    $responseData = json_decode($response, true);
    if (isset($responseData['id'])) {
        // Order created successfully
        $razorpayOrderId = $responseData['id'];
    } else {
        echo "Error: " . print_r($responseData, true);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            text-align: center;
            background-color: #fff;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
            border-radius: 8px;
        }
        h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .btn {
            background-color: #F37254;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #E06F4F;
        }
        .total-amount {
            font-size: 18px;
            margin: 20px 0;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Complete Your Payment</h2>
        <p class="total-amount">Total Amount: ₹<?= number_format($totalAmount / 100, 2) ?></p>
        <button id="pay-now" class="btn">Pay Now</button>
    </div>

    <script>
        document.getElementById('pay-now').onclick = function(e) {
            e.preventDefault();

            // Creating the Razorpay options object
            var options = {
                "key": "<?php echo addslashes($keyId); ?>", // Razorpay API Key
                "amount": "<?php echo $totalAmount; ?>", // Amount in paise
                "currency": "INR",
                "name": "Your E-commerce Site",
                "description": "Order #<?php echo $orderId; ?>",
                "image": "https://example.com/logo.png", // Optional, logo for your site
                "order_id": "<?php echo addslashes($razorpayOrderId); ?>", // Razorpay order ID
                "callback_url": "verify_payment.php", // URL where payment status will be sent
                "prefill": {
                    "name": "<?php echo addslashes($_SESSION['user_name']); ?>", // Optional pre-fill details
                    "email": "<?php echo addslashes($_SESSION['user_email']); ?>",
                    "contact": "<?php echo addslashes($_SESSION['user_phone']); ?>"
                },
                "theme": {
                    "color": "#F37254"
                }
            };

            console.log("Razorpay options: ", options);

            var rzp1 = new Razorpay(options);
            rzp1.open();
        }
    </script>
</body>
</html>
