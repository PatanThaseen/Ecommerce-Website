<?php
session_start(); // Start the session

// Destroy the session
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

// Set a flag or message to inform the user about the logout success
$_SESSION['logout_message'] = 'You have successfully logged out of your account.';

// Redirect to the logout success page
header('Location: logout_success.php');
exit();
?>

