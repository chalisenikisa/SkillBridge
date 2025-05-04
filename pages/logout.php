<?php
session_start();

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Start a new session to set a logout message
session_start();
$_SESSION['errmsg'] = "You have successfully logged out.";

// Redirect to login page
header("Location: index.php");
exit();
?>
