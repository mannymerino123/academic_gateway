<?php
session_start();

// Check if the user is logged in
if(!isset($_SESSION['A_username']) ) {
    // Redirect to login page if not logged in
    header("Location: login_gateway.php");
    exit();
}

// Destroy the session
session_destroy();

// Redirect to the login page after logout
header("Location: admin_loging.php");
exit();
?>
