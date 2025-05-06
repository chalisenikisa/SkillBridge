<?php
session_start();
include('includes/config.php');

// Redirect if not logged in
if (!isset($_SESSION['login'])) {
    header('location:index.php');
    exit();
}

$studentName = $_SESSION['sname'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<?php include('includes/header.php'); ?>

<div class="container" style="margin-top: 60px;">
    <div class="row">
        <div class="col-md-12">
            <h2 class="text-center">Welcome, <?php echo htmlentities($studentName); ?>!</h2>
            <hr>
        </div>
    </div>

    <div class="row text-center">
        <div class="col-md-3 col-sm-6 mb-3">
            <a href="enroll.php" class="btn btn-primary btn-block">
                <i class="fa fa-pencil-alt"></i> Enroll for Course
            </a>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <a href="enroll-history.php" class="btn btn-info btn-block">
                <i class="fa fa-history"></i> Enroll History
            </a>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <a href="my-profile.php" class="btn btn-success btn-block">
                <i class="fa fa-user"></i> My Profile
            </a>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <a href="change-password.php" class="btn btn-warning btn-block">
                <i class="fa fa-key"></i> Change Password
            </a>
        </div>

        <div class="col-md-12 mt-4">
            <a href="logout.php" class="btn btn-danger btn-block">
                <i class="fa fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<!-- Scripts -->
<script src="assets/js/jquery-1.11.1.js"></script>
<script src="assets/js/bootstrap.js"></script>
</body>
</html>
