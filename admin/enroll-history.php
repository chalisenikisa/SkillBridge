<?php
session_start();
include('includes/config.php');

// Redirect if admin not logged in
if (!isset($_SESSION['alogin']) || strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Enroll History</title>
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <link href="../assets/css/style.css" rel="stylesheet" />
</head>

<body>
<?php include('includes/header.php'); ?>
<?php if ($_SESSION['alogin'] != "") include('includes/menubar.php'); ?>

<div class="content-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-head-line">Enroll History</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <!-- Enroll Table -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Enroll History
                    </div>
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
                                        <th>Semester</th>
                                        <th>Enrollment Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $sql = mysqli_query($con, "
                                    SELECT 
                                        ce.course AS cid,
                                        c.courseName AS courname,
                                        s.session AS session,
                                        d.department AS dept,
                                        ce.enrollDate AS edate,
                                        sem.semester AS sem,
                                        st.studentName AS sname,
                                        st.StudentRegno AS sregno
                                    FROM 
                                        courseenrolls ce
                                    JOIN course c ON c.id = ce.course
                                    JOIN session s ON s.id = ce.session
                                    JOIN department d ON d.id = ce.department
                                    JOIN semester sem ON sem.id = ce.semester
                                    JOIN students st ON st.StudentRegno = ce.studentRegno
                                ");
                                $cnt = 1;
                                while ($row = mysqli_fetch_array($sql)) {
                                ?>
                                    <tr>
                                        <td><?php echo $cnt; ?></td>
                                        <td><?php echo htmlentities($row['sname']); ?></td>
                                        <td><?php echo htmlentities($row['sregno']); ?></td>
                                        <td><?php echo htmlentities($row['courname']); ?></td>
                                        <td><?php echo htmlentities($row['dept']); ?></td>
                                        <td><?php echo htmlentities($row['sem']); ?></td>
                                        <td><?php echo htmlentities($row['edate']); ?></td>
                                        <td>
                                            <a href="print.php?id=<?php echo urlencode($row['cid']); ?>" target="_blank" class="btn btn-primary">
                                                <i class="fa fa-print"></i> Print
                                            </a>
                                        </td>
                                    </tr>
                                <?php 
                                    $cnt++;
                                } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- End Enroll Table -->
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
<script src="../assets/js/jquery-1.11.1.js"></script>
<script src="../assets/js/bootstrap.js"></script>
</body>
</html>
