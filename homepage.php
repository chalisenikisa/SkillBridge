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

    <!-- Bootstrap & Font Awesome -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/css/font-awesome.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f4f8;
            margin: 0;
        }

        /* Branding Section */
        .brand-header {
            background-color: #003c3c;
            color: white;
            padding: 40px 0;
            text-align: center;
            border-bottom: 5px solid #d4af37;
        }

        .brand-header img {
            height: 70px;
            margin-bottom: 10px;
        }

        .brand-header h1 {
            font-size: 2.8rem;
            margin: 10px 0;
            font-weight: bold;
            color: #d4af37;
        }

        .brand-header p {
            font-size: 1.1rem;
            color: #e0e0e0;
        }

        /* Navigation Bar */
        .navbar {
            background-color: #004d4d;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .nav-link {
            color: white !important;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-link i {
            margin-right: 8px;
        }

        .nav-link:hover {
            color: #d4af37 !important;
        }

        .navbar-toggler {
            border-color: rgba(255,255,255,0.2);
        }

        .navbar-toggler-icon {
            color: white;
        }

        /* Main Content */
        .content {
            max-width: 900px;
            background: white;
            margin: 60px auto;
            padding: 50px 30px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            text-align: center;
        }

        .content h2 {
            font-size: 2rem;
            margin-bottom: 20px;
            color: #003c3c;
        }

        .content p {
            color: #555;
            margin-bottom: 40px;
        }

        .content img {
            max-width: 220px;
            margin: 20px;
            transition: transform 0.3s;
        }

        .content img:hover {
            transform: scale(1.05);
        }

        .footer {
            text-align: center;
            padding: 20px 0;
            background: #003c3c;
            color: white;
            margin-top: 60px;
            font-size: 0.95rem;
        }

        @media (max-width: 768px) {
            .brand-header h1 {
                font-size: 2.2rem;
            }
            .content {
                padding: 40px 20px;
            }
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
        <a class="navbar-brand text-white font-weight-bold" href="#">
            <i class="fa fa-graduation-cap"></i> OCR System
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span><i class="fa fa-bars text-white"></i></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="homepage.php"><i class="fa fa-home"></i> Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin/index.php"><i class="fa fa-user-shield"></i> Admin Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="student/index.php"><i class="fa fa-user-graduate"></i> Student Login</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="content">
    <h2>Welcome to the Online Course Registration Portal</h2>
    <p>Manage your educational journey with ease. Choose your role below:</p>

    <!-- Logos -->
    <img src="assets/img/college.png" alt="College Logo">
    <img src="assets/img/student.png" alt="Student Logo">
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
