<?php
session_start();
include('includes/config.php');

// Redirect if not logged in
if (!isset($_SESSION['alogin']) || strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit();
}

// Get counts for dashboard statistics
$studentCount = 0;
$courseCount = 0;
$departmentCount = 0;
$enrollmentCount = 0;

// Count students
$query = "SELECT COUNT(*) as count FROM students";
$result = $con->query($query);
if ($result && $row = $result->fetch_assoc()) {
    $studentCount = $row['count'];
}

// Count courses
$query = "SELECT COUNT(*) as count FROM course";
$result = $con->query($query);
if ($result && $row = $result->fetch_assoc()) {
    $courseCount = $row['count'];
}

// Count departments
$query = "SELECT COUNT(*) as count FROM department";
$result = $con->query($query);
if ($result && $row = $result->fetch_assoc()) {
    $departmentCount = $row['count'];
}

// Count enrollments
$query = "SELECT COUNT(*) as count FROM courseenrolls";
$result = $con->query($query);
if ($result && $row = $result->fetch_assoc()) {
    $enrollmentCount = $row['count'];
}

// Get recent enrollments
$recentEnrollments = [];
$query = "SELECT courseenrolls.*, course.courseName, students.studentName 
          FROM courseenrolls 
          JOIN course ON courseenrolls.course = course.id 
          JOIN students ON courseenrolls.studentRegno = students.StudentRegno 
          ORDER BY enrollDate DESC LIMIT 5";
$result = $con->query($query);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $recentEnrollments[] = $row;
    }
}

// Get recent students
$recentStudents = [];
$query = "SELECT * FROM students ORDER BY creationDate DESC LIMIT 5";
$result = $con->query($query);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $recentStudents[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Dashboard | SkillBridge</title>
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <link href="../assets/css/style.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            line-height: 1.6;
        }

        .navbar-brand {
            padding: 15px;
        }

        .menu-section {
            background: linear-gradient(to right, #2a2b75, #226a8b);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        #menu-top a {
            padding: 15px 20px;
            transition: all 0.3s ease;
        }

        #menu-top a:hover,
        #menu-top a.active {
            background-color: rgba(255, 255, 255, 0.1);
            border-bottom: 3px solid #33aa79;
        }

        .dashboard-container {
            padding: 20px;
        }

        .page-head-line {
            color: #2a2b75;
            font-weight: 600;
            padding-bottom: 15px;
            border-bottom: 2px solid #33aa79;
            margin-bottom: 30px;
            font-size: 24px;
        }

        .dashboard-stat {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .dashboard-stat:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .dashboard-stat::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
        }

        .stat-students::before {
            background: linear-gradient(to right, #2a2b75, #33aa79);
        }

        .stat-courses::before {
            background: linear-gradient(to right, #33aa79, #226a8b);
        }

        .stat-departments::before {
            background: linear-gradient(to right, #226a8b, #2a2b75);
        }

        .stat-enrollments::before {
            background: linear-gradient(to right, #33aa79, #2a2b75);
        }

        .stat-icon {
            font-size: 48px;
            color: #2a2b75;
            opacity: 0.8;
            margin-bottom: 15px;
        }

        .stat-number {
            font-size: 36px;
            font-weight: 700;
            color: #2a2b75;
            margin-bottom: 5px;
        }

        .stat-title {
            font-size: 16px;
            color: #666;
            font-weight: 500;
        }

        .quick-links {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .quick-links h4 {
            color: #2a2b75;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .quick-link {
            display: block;
            padding: 12px 15px;
            border-radius: 4px;
            margin-bottom: 10px;
            background-color: #f8f9fa;
            color: #333;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .quick-link:hover {
            background-color: #2a2b75;
            color: #fff;
            transform: translateX(5px);
        }

        .quick-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .recent-activity {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .recent-activity h4 {
            color: #2a2b75;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .activity-item {
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-title {
            font-weight: 500;
            color: #333;
            margin-bottom: 5px;
        }

        .activity-time {
            font-size: 12px;
            color: #999;
        }

        .sidebar {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            padding: 0;
            overflow: hidden;
        }

        .sidebar a {
            display: block;
            padding: 15px 20px;
            color: #333;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .sidebar a:hover {
            background-color: #f8f9fa;
            border-left: 3px solid #33aa79;
            color: #2a2b75;
        }

        .sidebar a.active {
            background-color: #f8f9fa;
            border-left: 3px solid #2a2b75;
            color: #2a2b75;
            font-weight: 500;
        }

        .welcome-message {
            background: linear-gradient(to right, #2a2b75, #226a8b);
            color: #fff;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .welcome-message h3 {
            margin-top: 0;
            font-weight: 600;
        }

        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background-color: #2a2b75;
            color: #fff;
            border: none;
            padding: 12px 15px;
        }

        .table tbody td {
            padding: 12px 15px;
            vertical-align: middle;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.02);
        }
    </style>
</head>

<body>
    <!-- HEADER START -->
    <div class="menu-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="navbar-collapse collapse ">
                        <ul id="menu-top" class="nav navbar-nav navbar-right">
                            <li><a href="../homepage.php">Home</a></li>
                            <li><a href="logout.php">Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- HEADER END -->

    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="page-head-line">Admin Dashboard</h4>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <!-- SIDEBAR MENU -->
                    <div class="sidebar">
                        <a href="dashboard.php" class="active"><i class="fa fa-dashboard" style="margin-right: 10px;"></i> Dashboard</a>
                        <a href="session.php"><i class="fa fa-calendar" style="margin-right: 10px;"></i> Session</a>
                        <a href="semester.php"><i class="fa fa-book" style="margin-right: 10px;"></i> Semester</a>
                        <a href="department.php"><i class="fa fa-building" style="margin-right: 10px;"></i> Department</a>
                        <a href="course.php"><i class="fa fa-bookmark" style="margin-right: 10px;"></i> Course</a>
                        <a href="student-registration.php"><i class="fa fa-registered" style="margin-right: 10px;"></i> Registration</a>
                        <a href="manage-students.php"><i class="fa fa-users" style="margin-right: 10px;"></i> Manage Students</a>
                        <a href="enroll-history.php"><i class="fa fa-history" style="margin-right: 10px;"></i> Enroll History</a>
                        <a href="news.php"><i class="fa fa-newspaper-o" style="margin-right: 10px;"></i> News</a>
                        <a href="logout.php"><i class="fa fa-sign-out" style="margin-right: 10px;"></i> Logout</a>
                    </div>
                    <!-- SIDEBAR MENU END -->

                    <!-- QUICK LINKS -->
                    <div class="quick-links">
                        <h4>Quick Links</h4>
                        <a href="course.php" class="quick-link"><i class="fa fa-plus"></i> Add New Course</a>
                        <a href="student-registration.php" class="quick-link"><i class="fa fa-user-plus"></i> Register Student</a>
                        <a href="news.php" class="quick-link"><i class="fa fa-bullhorn"></i> Post Announcement</a>
                        <a href="change-password.php" class="quick-link"><i class="fa fa-lock"></i> Change Password</a>
                    </div>
                    <!-- QUICK LINKS END -->
                </div>

                <div class="col-md-9">
                    <!-- WELCOME MESSAGE -->
                    <div class="welcome-message">
                        <h3>Welcome, <?php echo $_SESSION['alogin']; ?>!</h3>
                        <p>This is your admin dashboard where you can manage courses, students, and enrollments.</p>
                    </div>
                    <!-- WELCOME MESSAGE END -->

                    <!-- STATISTICS -->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="dashboard-stat stat-students">
                                <div class="stat-icon">
                                    <i class="fa fa-users"></i>
                                </div>
                                <div class="stat-number"><?php echo $studentCount; ?></div>
                                <div class="stat-title">Students</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="dashboard-stat stat-courses">
                                <div class="stat-icon">
                                    <i class="fa fa-book"></i>
                                </div>
                                <div class="stat-number"><?php echo $courseCount; ?></div>
                                <div class="stat-title">Courses</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="dashboard-stat stat-departments">
                                <div class="stat-icon">
                                    <i class="fa fa-building"></i>
                                </div>
                                <div class="stat-number"><?php echo $departmentCount; ?></div>
                                <div class="stat-title">Departments</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="dashboard-stat stat-enrollments">
                                <div class="stat-icon">
                                    <i class="fa fa-graduation-cap"></i>
                                </div>
                                <div class="stat-number"><?php echo $enrollmentCount; ?></div>
                                <div class="stat-title">Enrollments</div>
                            </div>
                        </div>
                    </div>
                    <!-- STATISTICS END -->

                    <div class="row">
                        <!-- RECENT ENROLLMENTS -->
                        <div class="col-md-6">
                            <div class="recent-activity">
                                <h4><i class="fa fa-history"></i> Recent Enrollments</h4>
                                <?php if (count($recentEnrollments) > 0): ?>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Student</th>
                                                    <th>Course</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($recentEnrollments as $enrollment): ?>
                                                    <tr>
                                                        <td><?php echo htmlentities($enrollment['studentName']); ?></td>
                                                        <td><?php echo htmlentities($enrollment['courseName']); ?></td>
                                                        <td><?php echo isset($enrollment['enrollDate']) && $enrollment['enrollDate'] ? htmlentities(date('d M Y', strtotime($enrollment['enrollDate']))) : 'N/A'; ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="text-right">
                                        <a href="enroll-history.php" class="btn btn-sm btn-primary">View All</a>
                                    </div>
                                <?php else: ?>
                                    <p>No recent enrollments found.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <!-- RECENT ENROLLMENTS END -->

                        <!-- RECENT STUDENTS -->
                        <div class="col-md-6">
                            <div class="recent-activity">
                                <h4><i class="fa fa-user-plus"></i> Recently Added Students</h4>
                                <?php if (count($recentStudents) > 0): ?>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Reg No</th>
                                                    <th>Name</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($recentStudents as $student): ?>
                                                    <tr>
                                                        <td><?php echo htmlentities($student['StudentRegno']); ?></td>
                                                        <td><?php echo htmlentities($student['studentName']); ?></td>
                                                        <td><?php echo isset($student['creationDate']) && $student['creationDate'] ? htmlentities(date('d M Y', strtotime($student['creationDate']))) : 'N/A'; ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="text-right">
                                        <a href="manage-students.php" class="btn btn-sm btn-primary">View All</a>
                                    </div>
                                <?php else: ?>
                                    <p>No recent students found.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <!-- RECENT STUDENTS END -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <?php include('includes/footer.php'); ?>
    <!-- FOOTER END -->

    <script src="../assets/js/jquery-1.11.1.js"></script>
    <script src="../assets/js/bootstrap.js"></script>
</body>

</html>