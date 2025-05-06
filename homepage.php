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
    /* Global Styles */
    body {
        font-family: 'Segoe UI', sans-serif;
        background-color: #f0f4f8;
        margin: 0;
        padding: 0;
        overflow-x: hidden;
    }

    /* Branding Section */
    .brand-header {
        background-color: #003c3c;
        color: white;
        padding: 40px 0;
        text-align: center;
        border-bottom: 5px solid #d4af37;
        width: 100%;
    }

    .brand-header img {
        height: 70px;
        margin-bottom: 10px;
        max-width: 100%; /* Ensures logo is responsive */
    }

    .brand-header h1 {
        font-size: 3rem;
        margin: 10px 0;
        font-weight: bold;
        color: #d4af37;
        line-height: 1.2;
    }

    .brand-header p {
        font-size: 1.1rem;
        color: #e0e0e0;
        line-height: 1.5;
    }

    /* Navbar Styles */
    .navbar {
        background-color: #004d4d;
        padding: 0.5rem 1rem;
    }

    .navbar .navbar-toggler {
        border-color: rgba(255, 255, 255, 0.3);
    }

    .nav-link {
        color: white !important;
        font-weight: 500;
        padding: 10px 15px;
        font-size: 1.1rem;
    }

    .nav-link i {
        margin-right: 6px;
    }

    .nav-link:hover {
        color: #d4af37 !important;
    }

    /* Mobile and Tablet Responsiveness */
    @media (max-width: 768px) {
        .brand-header h1 {
            font-size: 2.2rem;
        }

        .brand-header p {
            font-size: 1rem;
        }

        .navbar .nav-link {
            font-size: 1rem;
            padding: 8px 10px;
        }
    }

    /* Larger Screens */
    @media (min-width: 992px) {
        .navbar .nav-link {
            font-size: 1.2rem;
            padding: 12px 20px;
        }
    }

    /* Ensuring full screen fit */
    .container-fluid {
        width: 100%;
        padding-left: 0;
        padding-right: 0;
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
