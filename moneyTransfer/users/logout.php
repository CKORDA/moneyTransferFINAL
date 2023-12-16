<?php
// Start the session
session_start();

// Check if the user is logged in
if (isset($_SESSION['user'])) {
    // Clear all session variables
    session_unset();

    // Destroy the session
    session_destroy();

    // Redirect to the login page or home page
    header("Location: login.php"); // Change this to the desired location
    exit();
} else {
    // If the user is not logged in, you might redirect them to the login page
    header("Location: login.php"); // Change this to the login page
    exit();
}
?>
