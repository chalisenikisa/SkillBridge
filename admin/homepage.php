<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Online Course Registration - Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
        }
        .header {
            background-color: #691C0F;
            color: white;
            padding: 20px 0;
            text-align: center;
        }
        .nav-link {
            color: white !important;
        }
        .nav-link:hover {
            text-decoration: underline;
        }
        .content {
            padding: 40px;
        }
    </style>
</head>
<body>

<!-- Header -->
<div class="header">
    <div class="container">
        <h2>Online Course Registration</h2>
    </div>
</div>

<!-- Navigation Menu -->
<nav class="navbar navbar-inverse" style="margin-bottom: 0;">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">OCR System</a>
        </div>
        <ul class="nav navbar-nav navbar-right">
            <li><a class="nav-link" href="index.php">HOME</a></li>
            <li><a class="nav-link" href="admin/index.php">ADMIN LOGIN</a></li>
            <li><a class="nav-link" href="student/index.php">STUDENT LOGIN</a></li>
        </ul>
    </div>
</nav>

<!-- Main Content -->
<div class="content text-center">
    <div class="container">
        <h3>Welcome to the Online Course Registration Portal</h3>
        <p>Please choose from the options above to log in as an Admin or Student.</p>
        <img src="assets/img/college.png" alt="College" class="img-responsive center-block" style="max-width: 300px;">
    </div>
</div>

<!-- Footer -->
<?php include("includes/footer.php"); ?>

<!-- Scripts -->
<script src="assets/js/jquery-1.11.1.js"></script>
<script src="assets/js/bootstrap.js"></script>
</body>
</html>
