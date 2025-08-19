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

        /* Sidebar */
        .sidebar {
            width: 240px;
            background: linear-gradient(180deg, #007bff, #0056b3);
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            overflow-y: auto;
            padding-top: 20px;
            color: #fff;
        }
        .sidebar a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #f1f1f1;
            text-decoration: none;
            transition: 0.3s;
        }
        .sidebar a:hover {
            background-color: rgba(255,255,255,0.1);
            border-left: 4px solid #ffc107;
            color: #fff;
        }
        .sidebar i {
            margin-right: 10px;
        }

        /* Main Content */
        .main-content {
            margin-left: 240px;
            padding: 30px;
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
    <div class="sidebar">
            <a href="enroll.php">
                <i class="fas fa-pencil-alt"></i> Enroll for Course
            </a>
            <a href="recommendations.php" class="active">
                <i class="fas fa-lightbulb"></i> Recommended Courses
            </a>
            <a href="enroll-history.php">
                <i class="fas fa-history"></i> Enroll History
            </a>
            <a href="my-profile.php">
                <i class="fas fa-user"></i> My Profile
            </a>
            <a href="logout.php">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
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

