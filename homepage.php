<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Skillbridge - Online Course Registration</title>
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

        .brand-header {
            background-color: #003c3c;
            color: white;
            padding: 30px 0;
            text-align: center;
        }

        .brand-header img {
            max-height: 60px;
            margin-bottom: 10px;
        }

        .brand-header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            font-weight: bold;
            color: #d4af37;
        }

        .brand-header p {
            font-size: 1.1rem;
            color: #ccc;
        }

        .navbar {
            background-color: #005050;
            border-radius: 0;
        }

        .navbar-brand, .nav-link {
            color: white !important;
        }

        .nav-link:hover {
            color: #d4af37 !important;
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
            background: #003c3c;
            color: white;
            margin-top: 40px;
        }
    </style>
</head>
<body>

<!-- Brand Header -->
<div class="brand-header">
    <img src="assets/img/skillbridge_logo.png" alt="Skillbridge Logo">
    <h1>Skillbridge</h1>
    <p>Ignite Your Curiosity, Explore Education</p>
</div>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="#">OCR System</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon" style="color:white;"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="homepage.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="admin/index.php">Admin Login</a></li>
                <li class="nav-item"><a class="nav-link" href="student/index.php">Student Login</a></li>
            </ul>
        </div>
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
    echo '<div class="footer">© ' . date('Y') . ' Skillbridge - Online Course Registration System</div>';
}
?>

<!-- Scripts -->
<script src="assets/js/jquery-1.11.1.js"></script>
<script src="assets/js/bootstrap.js"></script>
</body>
</html>
