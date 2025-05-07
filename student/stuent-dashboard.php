<?php
session_start();
include("includes/config.php");

// Check login
if (!isset($_SESSION['login'])) {
    header("Location: student/index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />

    <style>
        body {
            padding-top: 60px;
        }
        .sidebar {
            width: 220px;
            background-color: #f8f9fa;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            overflow-y: auto;
            border-right: 1px solid #ddd;
            padding-top: 20px;
        }
        .main-content {
            margin-left: 240px;
        }
    </style>
</head>
<body>

<?php include('includes/header.php'); ?>
<?php include('includes/sidebar.php'); ?>

<div class="container-fluid main-content">
    <div class="row">
        <div class="col-md-12">
            <h3>Welcome, <?php echo htmlentities($_SESSION['sname']); ?>!</h3>
            <p>This is your student dashboard.</p>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>
