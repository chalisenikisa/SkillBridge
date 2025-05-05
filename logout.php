<?php
session_start();
include("includes/config.php");

// Check if student is logged in
if (!empty($_SESSION['login'])) {
    date_default_timezone_set('Asia/Kathmandu');
    $ldate = date('Y-m-d H:i:s'); // Use MySQL-compatible datetime format

    $uid = $_SESSION['login'];

    // Update the latest log entry for this student
    $stmt = $con->prepare("
        UPDATE userlog 
        SET logout = ? 
        WHERE studentRegno = ? 
        ORDER BY id DESC 
        LIMIT 1
    ");
    $stmt->bind_param("ss", $ldate, $uid);
    $stmt->execute();
    $stmt->close();
}

// Destroy the session securely
session_unset();
session_destroy();

// Start new session to show logout message
session_start();
$_SESSION['errmsg'] = "You have successfully logged out.";

// Redirect to login page
header("Location: index.php");
exit();
?>
