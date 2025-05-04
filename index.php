
<?php
session_start();
include('includes/config.php');
error_reporting(0);

if(strlen($_SESSION['alogin'])==0) {
    header('location:login.php');
    exit();
}

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
</head>
<body>

<?php include('includes/header.php'); ?>
<?php include('includes/sidebar.php'); ?>

<div class="content-wrapper">
    <div class="container">
        <?php
            $allowed_pages = ['dashboard', 'session, 'semester', 'department', 'course', 'registration', 'manage students", 'enroll history', 'student logd', 'news', 'logout'];
            if (in_array($page, $allowed_pages)) {
                include("pages/$page.php");
            } else {
                echo "<h3>Page not found</h3>";
            }
        ?>
    </div>
</div>

<?php include('includes/footer.php'); ?>
</body>
</html>