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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

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

        /* Navbar */
        .navbar {
            background-color: #004d4d;
        }

        .nav-link {
            color: white !important;
            font-weight: 500;
        }

        .nav-link i {
            margin-right: 6px;
        }

        .nav-link:hover {
            color: #d4af37 !important;
        }

        .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.3);
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3e%3cpath stroke='rgba%28255, 255, 255, 1%29' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
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
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="#"><i class="fas fa-graduation-cap"></i> OCR System</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="homepage.php"><i class="fas fa-home"></i> Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin/index.php"><i class="fas fa-user-shield"></i> Admin Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="student/index.php"><i class="fas fa-user-graduate"></i> Student Login</a>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

</body>
</html>