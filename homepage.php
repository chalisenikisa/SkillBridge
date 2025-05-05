<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Online Course Registration - Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/css/font-awesome.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f5f5;
        }
        .header {
            background-color: #6d1d1d;
            color: white;
            padding: 40px 0;
            text-align: center;
        }
        .navbar {
            background-color: #343a40;
            border-radius: 0;
        }
        .navbar-brand, .nav-link {
            color: white !important;
        }
        .nav-link:hover {
            color: #ffc107 !important;
        }
        .content {
            padding: 60px 20px;
            background-color: white;
            margin: 40px auto;
            max-width: 800px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .content img {
            max-width: 250px;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            padding: 15px 0;
            background: #343a40;
            color: white;
            margin-top: 40px;
        }
    </style>
</head>
<body>

<!-- Header -->
<div class="header">
    <h1>Online Course Registration</h1>
    <p>Register and manage your courses easily</p>
</div>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="#">OCR System</a>
        <ul class="nav navbar-nav ml-auto">
            <li class="nav-item"><a class="nav-link" href="homepage.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="admin/index.php">Admin Login</a></li>
            <li class="nav-item"><a class="nav-link" href="student/index.php">Student Login</a></li>
        </ul>
    </div>
</nav>

<!-- Main Content -->
<div class="content">
    <h2>Welcome to the Online Course Registration Portal</h2>
    <p>Please log in as Admin or Student to begin.</p>
    
    <!-- College logo -->
    <img src="assets/img/college.png" alt="College Logo">

    <!-- Student logo -->
    <br><br>
    <img src="assets/img/student.png" alt="Student Logo" style="max-width: 200px;">
</div>

<!-- Footer -->
<?php 
$footerPath = "includes/footer.php";
if (file_exists($footerPath)) {
    include($footerPath);
} else {
    echo '<div class="footer">© ' . date('Y') . ' Online Course Registration System</div>';
}
?>

<!-- Scripts -->
<script src="assets/js/jquery-1.11.1.js"></script>
<script src="assets/js/bootstrap.js"></script>
</body>
</html>
