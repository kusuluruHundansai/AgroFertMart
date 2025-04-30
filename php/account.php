<?php
// Start the session to access session variables
session_start();

// Include the database connection and authentication files
require 'db.php';
require 'php/auth.php';

// Get the logged-in user's email from the session
$email = $_SESSION['user'] ?? '';

// Prepare the query to fetch the user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

// Include header file
include 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Account</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>

<div class="max-w-xl mx-auto mt-10 bg-white p-6 shadow rounded">
  <h2 class="text-2xl font-bold mb-4">My Account</h2>

  <?php if ($user): ?>
    <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
    <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone'] ?? 'Not Provided') ?></p>
    <p><strong>Address:</strong><br><?= nl2br(htmlspecialchars($user['address'] ?? 'Not Provided')) ?></p>
  <?php else: ?>
    <p>User not found.</p>
  <?php endif; ?>

</div>

<?php
// Include footer file
include 'includes/footer.php';
echo get_include_path(); // To see the current include path in your system

?>

</body>
</html>
