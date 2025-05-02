<?php
session_start();
include("includes/config.php");

// Only proceed if the user is logged in
if (!empty($_SESSION['login'])) {
    date_default_timezone_set('Asia/Kathmandu');
    $ldate = date('d-m-Y h:i:s A');

    $uid = $_SESSION['login'];

    // Safely update the latest userlog entry for the user
    $stmt = $con->prepare("UPDATE userlog SET logout = ? WHERE studentRegno = ? ORDER BY id DESC LIMIT 1");
    $stmt->bind_param("ss", $ldate, $uid);
    $stmt->execute();
}

// Clear all session data
session_unset();
session_destroy();

// Start new session to store logout message
session_start();
$_SESSION['errmsg'] = "You have successfully logged out";

// Redirect to login page
header("Location: index.php");
exit();
?>
