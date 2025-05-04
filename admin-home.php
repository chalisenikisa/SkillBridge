<?php
session_start();
include("includes/config.php");
if (!isset($_SESSION['alogin'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Admin Dashboard</title>
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <link href="../assets/css/style.css" rel="stylesheet" />
</head>
<body>

<?php include('includes/header.php'); ?>
<?php include('includes/sidebar.php'); ?> <!-- ✅ SIDEBAR HERE -->
<nav>
    <ul class="nav nav-pills nav-stacked">
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="manage-session.php">Session</a></li>
        <li><a href="manage-semester.php">Semester</a></li>
        <li><a href="manage-department.php">Department</a></li>
        <li><a href="manage-course.php">Course</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>

<div class="content-wrapper">
    <div class="container">
        <h3>Welcome, <?php echo htmlentities($_SESSION['alogin']); ?>!</h3>
        <p>This is your admin panel dashboard.</p>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<script src="../assets/js/jquery-1.11.1.js"></script>
<script src="../assets/js/bootstrap.js"></script>
</body>
</html>
