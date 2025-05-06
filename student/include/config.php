<?php
$servername = "localhost";
$username = "root";
$password = "";
define('DB_NAME', 'onlinecourse');
$dbname = "onlinecourse";

// Create connection
$con = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
