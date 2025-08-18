<?php
session_start();
include('../includes/config.php');         // DB connection
include('../includes/recommendations.php'); // recommendation function

if (strlen($_SESSION['login']) == 0) {   
    header('location:../index.php');
    exit();
} else {
    $studentRegNo = $_SESSION['login'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2 { color: #2c3e50; }
        h3 { margin-top: 30px; }
        ul { background: #ecf0f1; padding: 15px; border-radius: 8px; list-style-type: none; }
        li { margin: 5px 0; }
    </style>
</head>
<body>
    <h2>Welcome, <?php echo htmlentities($studentRegNo); ?> 👋</h2>

    <div class="recommendations">
        <?php showRecommendations($con, $studentRegNo); ?>
    </div>
</body>
</html>
