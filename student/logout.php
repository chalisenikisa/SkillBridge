<?php
session_start();
include("includes/config.php");

// Ensure user is logged in before attempting to log out
if (!empty($_SESSION['login'])) {
    // Set timezone
    date_default_timezone_set('Asia/Kathmandu');
    $ldate = date('d-m-Y h:i:s A', time());
    $uid = $_SESSION['login'];

    // Update logout time for latest user log entry
    $update = mysqli_query($con, "UPDATE userlog SET logout = '$ldate' WHERE studentRegno = '$uid' ORDER BY id DESC LIMIT 1");
}

// Clear session data
session_unset();
session_destroy();

// Optionally start a new session if you want to display a message
session_start();
$_SESSION['errmsg'] = "You have successfully logged out";

// Redirect to login page
header("Location: index.php");
exit;
?>
