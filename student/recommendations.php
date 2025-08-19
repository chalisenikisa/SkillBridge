<?php
session_start();
include('includes/config.php');
error_reporting(0);

// Redirect to login if not logged in
if (strlen($_SESSION['login']) == 0) {
    header('location:index.php');
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Recommended Courses</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Bootstrap and Custom Styles -->
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <link href="../assets/css/style.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: linear-gradient(to bottom, #2a2b75, #226a8b);
            padding: 20px 0;
            border-radius: 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .sidebar a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            margin-bottom: 5px;
        }

        .sidebar a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: rgba(255, 255, 255, 0.1);
            border-left: 3px solid #33aa79;
        }

        .content {
            flex: 1;
            padding: 30px;
            background-color: #f5f7fa;
        }

        .page-head-line {
            color: #2a2b75;
            font-weight: 600;
            padding-bottom: 15px;
            border-bottom: 2px solid #33aa79;
            margin-bottom: 30px;
            font-size: 24px;
        }

        /* Recommendation Cards */
        .recommendation-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            overflow: hidden;
        }

        .card-header {
            padding: 15px 20px;
            font-weight: 600;
            font-size: 18px;
            display: flex;
            align-items: center;
        }

        .content-based .card-header {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
        }

        .collaborative .card-header {
            background: linear-gradient(135deg, #9b59b6, #8e44ad);
            color: white;
        }

        .card-body {
            padding: 20px;
        }

        .course-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .course-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-radius: 6px;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
        }

        .course-item:hover {
            background-color: #e9ecef;
            transform: translateY(-2px);
        }

        .course-details h4 {
            margin: 0 0 5px 0;
            font-size: 16px;
            font-weight: 600;
        }

        .course-code {
            font-size: 14px;
            color: #6c757d;
            font-weight: normal;
        }

        .course-details p {
            margin: 0;
            font-size: 14px;
            color: #6c757d;
        }

        .btn-enroll {
            background: linear-gradient(135deg, #33aa79, #2a8c63);
            color: white;
            padding: 8px 15px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }

        .btn-enroll:hover {
            background: linear-gradient(135deg, #2a8c63, #1e6e4c);
            color: white;
            transform: translateY(-2px);
        }

        .empty-state {
            text-align: center;
            padding: 30px;
            color: #6c757d;
            font-size: 16px;
        }

        .empty-state i {
            font-size: 40px;
            margin-bottom: 15px;
            display: block;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
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

        <!-- Content -->
        <div class="content">
            <h3 class="page-head-line">Recommended Courses</h3>

            <?php

            // Handle enrollment
            if (isset($_POST['enroll_course']) && isset($_SESSION['login'])) {
                $course_id = intval($_POST['course_id']);
                $studentRegNo = $_SESSION['login'];

                // Check if already enrolled
                $check = mysqli_query($con, "SELECT * FROM courseenrolls WHERE studentRegno='$studentRegNo' AND course='$course_id'");
                if (mysqli_num_rows($check) > 0) {
                    echo "<p style='color:orange;'>You are already enrolled in this course.</p>";
                    exit;
                }

                // Fetch student info
                $stmt = $con->prepare("SELECT * FROM students WHERE StudentRegno = ?");
                $stmt->bind_param("s", $studentRegNo);
                $stmt->execute();
                $userResult = $stmt->get_result();
                $user = $userResult->fetch_assoc();

                if (!$user) {
                    echo "<p style='color:red;'>Student not found.</p>";
                    exit;
                }

                // Map student session to session_id
                $session_id = $department_id = $semester_id = $level_id = null;

                if (!empty($user['session'])) {
                    $res = mysqli_q