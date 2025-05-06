<?php
session_start();

// Include the student login check to ensure the user is logged in
include('../student-login.php');

// If not logged in, redirect to the login page
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit();
}

include("includes/config.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="assets/css/style.css" rel="stylesheet" />
</head>
<body>

<?php include('includes/header.php'); ?>

<div class="container">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <?php include('includes/student-sidebar.php'); ?>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <h4>Welcome, <?php echo htmlentities($_SESSION['sname']); ?></h4>
            
            <!-- Load specific content based on ?page=... -->
            <?php
            if (isset($_GET['page'])) {
                $page = $_GET['page'];
                include("$page.php"); // Ensure input is validated/sanitized in production
            } else {
                echo "<p>Select an option from the sidebar.</p>";
            }
            ?>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<script src="assets/js/jquery-1.11.1.js"></script>
<script src="assets/js/bootstrap.js"></script>
</body>
</html>
