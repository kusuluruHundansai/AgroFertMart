<?php
function sendOrderConfirmation($toEmail, $orderId, $total) {
    $subject = "Order Confirmation - Order #$orderId";
    $message = "
        <html>
        <head>
          <title>Order Confirmation</title>
        </head>
        <body>
          <h2>Thank you for your order!</h2>
          <p>Your Order ID is <strong>#$orderId</strong></p>
          <p>Total Amount Paid: <strong>₹" . number_format($total, 2) . "</strong></p>
          <p>We are preparing your items for delivery. You’ll receive a notification once they are shipped.</p>
          <br>
          <p>Regards,<br>AgriStore Team</p>
        </body>
        </html>
    ";

    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    // Additional headers
    $headers .= "From: AgriStore <no-reply@agristore.com>" . "\r\n";

    // Send the email
    mail($toEmail, $subject, $message, $headers);
}
