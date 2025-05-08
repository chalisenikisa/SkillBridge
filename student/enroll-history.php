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
</head>

<body>
    <?php include('includes/header.php'); ?>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                <?php include('includes/sidebar.php'); ?>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <h1 class="page-head-line">Enroll History</h1>

                <div class="panel panel-default">
                    <div class="panel-heading">Enroll History</div>
                    <div class="panel-body">
                        <div class="table-responsive table-bordered">
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
                                ";

                                $stmt = $con->prepare($query);
                                $stmt->bind_param("s", $studentRegNo);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $cnt = 1;

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
                                                    <button class='btn btn-primary btn-sm'><i class='fa fa-print'></i> Print</button>
                                                </a>
                                            </td>
                                          </tr>";
                                    $cnt++;
                                }

                                $stmt->close();
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div> <!-- End of Main Content -->
        </div> <!-- End of Row -->
    </div> <!-- End of Container -->

    <?php include('includes/footer.php'); ?>

    <!-- Scripts -->
    <script src="../assets/js/jquery-1.11.1.js"></script>
    <script src="../assets/js/bootstrap.js"></script>
</body>
</html>
