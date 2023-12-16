<?php

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    $username = "Guest"; // or any default value
}

// Encode the username to prevent XSS attacks
$encodedUsername = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
?>
