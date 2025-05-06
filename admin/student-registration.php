<?php
session_start();
if (!isset($_SESSION['alogin']) || strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - Student Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    
    <!-- Bootstrap CSS -->
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />

    <style>
        * { box-sizing: border-box; }
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
        }
        .wrapper { display: flex; }
        .sidebar {
            width: 220px;
            background-color: #f8f9fa;
            border-right: 1px solid #ddd;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
            padding-top: 20px;
        }
        .sidebar a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #333;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .sidebar a:hover { background-color: #e9ecef; }
        .sidebar i { margin-right: 10px; }
        .main-content {
            margin-left: 220px;
            padding: 30px;
            width: calc(100% - 220px);
        }
        .page-head-line {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 25px;
            color: #333;
        }
        .panel {
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        .panel-heading {
            padding: 10px 15px;
            background: #007bff;
            color: #fff;
            font-weight: bold;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
        }
        .panel-body { padding: 15px; }
        table th, table td { vertical-align: middle !important; }
    </style>
</head>
<body>

<div class="wrapper">
    <?php include('includes/sidebar.php'); ?>

    <div class="main-content">
        <h1 class="page-head-line">STUDENT REGISTRATION</h1>

        <!-- Student Registration Form -->
        <div class="panel">
            <div class="panel-heading">Register New Student</div>
            <div class="panel-body">
                <form method="post">
                    <div class="form-group">
                        <label for="studentname">Full Name</label>
                        <input type="text" class="form-control" id="studentname" name="studentname" placeholder="Enter Full Name" required />
                    </div>
                    <div class="form-group">
                        <label for="email">Email ID</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" required />
                    </div>
                    <div class="form-group">
                        <label for="course">Course</label>
                        <input type="text" class="form-control" id="course" name="course" placeholder="Enter Course" required />
                    </div>
                    <div class="form-group">
                        <label for="regdate">Registration Date</label>
                        <input type="date" class="form-control" id="regdate" name="regdate" required />
                    </div>
                    <button type="submit" class="btn btn-primary">Register Student</button>
                </form>
            </div>
        </div>

        <!-- Registered Students Table -->
        <div class="panel">
            <div class="panel-heading">Registered Students</div>
            <div class="panel-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Course</th>
                            <th>Registration Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Sample static rows. Replace with PHP database logic later. -->
                        <tr>
                            <td>1</td>
                            <td>John Doe</td>
                            <td>john@example.com</td>
                            <td>BCA</td>
                            <td>2025-04-20</td>
                            <td><button class="btn btn-danger btn-sm">Delete</button></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Jane Smith</td>
                            <td>jane@example.com</td>
                            <td>MCA</td>
                            <td>2025-04-22</td>
                            <td><button class="btn btn-danger btn-sm">Delete</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- JS Scripts -->
<script src="../assets/js/jquery-1.11.1.js"></script>
<script src="../assets/js/bootstrap.js"></script>
</body>
</html>
