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
    <title>Enroll History</title>
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

        .history-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }

        .history-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(to right, #2a2b75, #33aa79);
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background-color: #f8f9fa;
            color: #2a2b75;
            font-weight: 600;
            border-bottom: 2px solid #eaeaea;
            padding: 12px 15px;
        }

        .table tbody td {
            padding: 12px 15px;
            vertical-align: middle;
            border-top: 1px solid #eaeaea;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.02);
        }

        .btn-print {
            background: linear-gradient(to right, #2a2b75, #33aa79);
            border: none;
            color: white;
            padding: 8px 15px;
            border-radius: 4px;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn-print:hover {
            background: linear-gradient(to right, #33aa79, #2a2b75);
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.15);
            color: white;
        }

        .empty-history {
            text-align: center;
            padding: 40px 20px;
            color: #666;
        }

        .empty-history i {
            font-size: 48px;
            color: #ddd;
            margin-bottom: 15px;
            display: block;
        }
    </style>
</head>

<body>
    <?php include('includes/header.php'); ?>

    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <a href="enroll.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'enroll.php' ? 'active' : ''; ?>">
                <i class="fas fa-book-open"></i> Enroll for Course
            </a>
            <a href="enroll-history.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'enroll-history.php' ? 'active' : ''; ?>">
                <i class="fas fa-history"></i> Enroll History
            </a>
            <a href="my-profile.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'my-profile.php' ? 'active' : ''; ?>">
                <i class="fas fa-user"></i> My Profile
            </a>
            <a href="change-password.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'change-password.php' ? 'active' : ''; ?>">
                <i class="fas fa-key"></i> Change Password
            </a>
            <a href="logout.php">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>

        <!-- Main Content -->
        <div class="content">
            <h2 class="page-head-line"><i class="fas fa-history me-2"></i> Enroll History</h2>

            <div class="history-card">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Course Name</th>
                                <th>Session</th>
                                <th>Department</th>
                                <th>Level</th>
                                <th>Semester</th>
                                <th>Enrollment Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $studentRegNo = $_SESSION['login'];
                            $query = "
                                SELECT
                                    courseenrolls.course AS cid,
                                    course.courseName AS courname,
                                    session.session AS session,
                                    department.department AS dept,
                                    level.level AS level,
                                    semester.semester AS sem,
                                    courseenrolls.enrollDate AS edate
                                FROM courseenrolls
                                JOIN course ON course.id = courseenrolls.course
                                JOIN session ON session.id = courseenrolls.session
                                JOIN department ON department.id = courseenrolls.department
                                JOIN level ON level.id = courseenrolls.level
                                JOIN semester ON semester.id = courseenrolls.semester
                                WHERE courseenrolls.studentRegno = ?
                                ORDER BY courseenrolls.enrollDate DESC
                            ";

                            $stmt = $con->prepare($query);
                            $stmt->bind_param("s", $studentRegNo);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $cnt = 1;

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                            <td>" . htmlentities($cnt) . "</td>
                                            <td>" . htmlentities($row['courname']) . "</td>
                                            <td>" . htmlentities($row['session']) . "</td>
                                            <td>" . htmlentities($row['dept']) . "</td>
                                            <td>" . htmlentities($row['level']) . "</td>
                                            <td>" . htmlentities($row['sem']) . "</td>
                                            <td>" . htmlentities($row['edate']) . "</td>
                                            <td>
                                                <a href='print.php?id=" . htmlentities($row['cid']) . "' target='_blank'>
                                                    <button class='btn btn-print btn-sm'><i class='fas fa-print me-1'></i> Print</button>
                                                </a>
                                            </td>
                                          </tr>";
                                    $cnt++;
                                }
                            } else {
                                echo "<tr>
                                        <td colspan='8'>
                                            <div class='empty-history'>
                                                <i class='fas fa-info-circle'></i>
                                                <p>You haven't enrolled in any courses yet.</p>
                                            </div>
                                        </td>
                                      </tr>";
                            }

                            $stmt->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div> <!-- End of Main Content -->
    </div> <!-- End of Wrapper -->

    <?php include('includes/footer.php'); ?>

    <!-- Scripts -->
    <script src="../assets/js/jquery-1.11.1.js"></script>
    <script src="../assets/js/bootstrap.js"></script>
</body>

</html>