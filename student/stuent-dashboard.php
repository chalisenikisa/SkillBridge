<?php
session_start();
include('includes/config.php');  // your DB connection
include('recommendations.php'); // our function

if(strlen($_SESSION['login'])==0) {   
    header('location:index.php');
    exit();
} else {
    $studentRegNo = $_SESSION['login'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h3 { color: #2c3e50; }
        ul { background: #ecf0f1; padding: 15px; border-radius: 8px; }
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
