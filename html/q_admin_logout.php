<?php
// Start the session
session_start();

// Check if the user is logged in
if(isset($_SESSION['admin_id'])) {
    // If logged in, destroy the session
    session_destroy();
    
    // Redirect to the login page or any other page you want
    header("Location: q_admin_login.php");
    exit;
} else {
    // If the user is not logged in, redirect them to the login page
    header("Location: q_admin_login.php");
    exit;
}
?>
