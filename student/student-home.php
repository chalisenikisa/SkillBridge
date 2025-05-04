<?php
session_start();
include('../includes/config.php');
if (!isset($_SESSION['slogin'])) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include('includes/header.php'); ?>
<?php include('includes/sidebar.php'); ?> <!-- ✅ Include menu -->
<nav>
    <ul class="nav nav-pills nav-stacked">
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="enroll-course.php">Enroll for Course</a></li>
        <li><a href="enroll-history.php">Enroll History</a></li>
        <li><a href="my-profile.php">My Profile</a></li>
        <li><a href="change-password.php">Change Password</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>


<div class="content-wrapper">
    <div class="container">
        <h3>Welcome, <?php echo htmlentities($_SESSION['slogin']); ?></h3>
        <p>Select a menu option to proceed.</p>
    </div>
</div>

<?php include('includes/footer.php'); ?>
</body>
</html>
