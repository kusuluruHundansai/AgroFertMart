<?php
session_start(); // Start the session

// Check if the user is logged in and has a session
if (isset($_SESSION['user'])) {
    session_unset();   // Unset all session variables
    session_destroy(); // Destroy the session
}

// Redirect the user to the home page or login page after logout
header('Location: login.php');
exit(); // Make sure the script doesn't execute further
?>
