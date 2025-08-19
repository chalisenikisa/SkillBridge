<?php
session_start();
include("includes/config.php");

// Check login
if (!isset($_SESSION['login'])) {
    header("Location:index.php");
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
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f9;
            margin: 0;
        }

        

        /* Main Content */
        .main-content {
            margin-left: 240px;
            padding: 30px;
        }

        .dashboard-header {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 2px 6px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .dashboard-header h3 {
            margin: 0;
            color: #333;
        }

        /* Dashboard Cards */
        .card {
            background: linear-gradient(135deg, #17a2b8, #138496);
            color: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            margin-bottom: 20px;
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card i {
            font-size: 40px;
            margin-bottom: 10px;
        }
        .card h4 {
            margin: 10px 0 0;
        }

        /* Footer */
        .footer {
            text-align: center;
            padding: 15px;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>

<?php include('includes/sidebar.php'); ?>

<div class="main-content">
    <div class="dashboard-header">
        <h3>Welcome, <?php echo htmlentities($_SESSION['sname']); ?> 👋</h3>
        <p>Here’s an overview of your activities.</p>
    </div>
<div class="row">
        <div class="col-md-4">
            <div class="card text-center">
                <i class="fa fa-pencil-square-o"></i>
                <h4>Enroll for Courses</h4>
            </div>
</div>

    
        </div>
        <div class="col-md-4">
            <div class="card text-center" style="background: linear-gradient(135deg, #28a745, #218838);">
                <i class="fa fa-lightbulb-o"></i>
                <h4>Recommendations Courses</h4>
            </div>

        <div class="col-md-4">
            <div class="card text-center" style="background: linear-gradient(135deg, #ffc107, #e0a800);">
                <i class="fa fa-history"></i>
                <h4>Enroll History</h4>
            </div>

    
        
        </div>
    </div>

    <div class="footer">
        © 2025 SkillBridge | Online Course Registration <br>
        <small>Empowering Students Through Quality Education</small>
    </div>
</div>

<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>

