<?php
session_start();
include('includes/config.php');

// Check login
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
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

    <!-- CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<?php include('includes/header.php'); ?>

<div class="container" style="margin-top: 50px;">
    <div class="row">
        <div class="col-md-12 text-center">
            <h3>Welcome, <?php echo htmlentities($studentName); ?>!</h3>
            <hr>
        </div>
    </div>

    <!-- Dashboard Icons -->
    <div class="row text-center">
        <div class="col-md-3 col-sm-6 mb-3">
            <a href="enroll.php" class="btn btn-outline-primary btn-block">
                <i class="fa fa-pencil-alt fa-2x"></i><br>Enroll for Course
            </a>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <a href="enroll-history.php" class="btn btn-outline-info btn-block">
                <i class="fa fa-history fa-2x"></i><br>Enroll History
            </a>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <a href="my-profile.php" class="btn btn-outline-success btn-block">
                <i class="fa fa-user fa-2x"></i><br>My Profile
            </a>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <a href="change-password.php" class="btn btn-outline-warning btn-block">
                <i class="fa fa-key fa-2x"></i><br>Change Password
            </a>
        </div>

        <div class="col-md-12 mt-4">
            <a href="logout.php" class="btn btn-outline-danger btn-block">
                <i class="fa fa-sign-out-alt fa-2x"></i><br>Logout
            </a>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<!-- JS -->
<script src="assets/js/jquery-1.11.1.js"></script>
<script src="assets/js/bootstrap.js"></script>
</body>
</html>
