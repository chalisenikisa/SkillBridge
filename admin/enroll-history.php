<?php
session_start();
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Admin | Enroll History</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <link href="../assets/css/style.css" rel="stylesheet" />
</head>

<body>
<?php include('includes/header.php'); ?>
<?php if ($_SESSION['alogin'] != "") { include('includes/menubar.php'); } ?>

<div class="content-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-head-line">Enroll History</h1>
            </div>
        </div>

        <!-- Enroll History Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Enroll History</div>
                    <div class="panel-body">
                        <div class="table-responsive table-bordered">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Student Name</th>
                                        <th>Student Reg No</th>
                                        <th>Course Name</th>
                                        <th>Department</th>
                                        <th>Session</th>
                                        <th>Semester</th>
                                        <th>Enrollment Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
<?php
$query = "SELECT 
            courseenrolls.id as enrollid,
            students.studentName as sname,
            students.StudentRegno as sregno,
            course.courseName as courname,
            department.department as dept,
            session.session as session,
            semester.semester as sem,
            courseenrolls.enrollDate as edate
          FROM courseenrolls
          JOIN students ON students.StudentRegno = courseenrolls.studentRegno
          JOIN course ON course.id = courseenrolls.course
          JOIN department ON department.id = courseenrolls.department
          JOIN session ON session.id = courseenrolls.session
          JOIN semester ON semester.id = courseenrolls.semester";

$sql = mysqli_query($con, $query);
$cnt = 1;
while ($row = mysqli_fetch_array($sql)) {
?>
                                    <tr>
                                        <td><?php echo $cnt; ?></td>
                                        <td><?php echo htmlentities($row['sname']); ?></td>
                                        <td><?php echo htmlentities($row['sregno']); ?></td>
                                        <td><?php echo htmlentities($row['courname']); ?></td>
                                        <td><?php echo htmlentities($row['dept']); ?></td>
                                        <td><?php echo htmlentities($row['session']); ?></td>
                                        <td><?php echo htmlentities($row['sem']); ?></td>
                                        <td><?php echo htmlentities($row['edate']); ?></td>
                                        <td>
                                            <a href="print.php?id=<?php echo $row['enrollid']; ?>" target="_blank">
                                                <button class="btn btn-primary"><i class="fa fa-print"></i> Print</button>
                                            </a>
                                        </td>
                                    </tr>
<?php 
$cnt++;
} 
?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- End Enroll History Table -->
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
<script src="../assets/js/jquery-1.11.1.js"></script>
<script src="../assets/js/bootstrap.js"></script>

</body>
</html>
