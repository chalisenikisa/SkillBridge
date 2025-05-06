<?php
session_start();
include('includes/config.php');
error_reporting(0);

// Redirect to login if session not set
if (strlen($_SESSION['login']) == 0) {
    header('location:index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Enroll History</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body>
<?php include('includes/header.php'); ?>
<?php include('includes/menubar.php'); ?>

<div class="content-wrapper">
    <div class="container">

        <div class="row">
            <div class="col-md-12">
                <h1 class="page-head-line">Enroll History</h1>
            </div>
        </div>

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
$regNo = $_SESSION['login'];

// Use prepared statement for security
$stmt = $con->prepare("
    SELECT 
        ce.course AS cid, 
        c.courseName AS courname, 
        s.session AS session, 
        d.department AS dept, 
        l.level AS level, 
        ce.enrollDate AS edate, 
        sem.semester AS sem 
    FROM courseenrolls ce
    JOIN course c ON c.id = ce.course 
    JOIN session s ON s.id = ce.session 
    JOIN department d ON d.id = ce.department 
    JOIN level l ON l.id = ce.level 
    JOIN semester sem ON sem.id = ce.semester  
    WHERE ce.studentRegno = ?
");

$stmt->bind_param("s", $regNo);
$stmt->execute();
$result = $stmt->get_result();

$cnt = 1;
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
?>
<tr>
    <td><?php echo $cnt; ?></td>
    <td><?php echo htmlentities($row['courname']); ?></td>
    <td><?php echo htmlentities($row['session']); ?></td>
    <td><?php echo htmlentities($row['dept']); ?></td>
    <td><?php echo htmlentities($row['level']); ?></td>
    <td><?php echo htmlentities($row['sem']); ?></td>
    <td><?php echo htmlentities($row['edate']); ?></td>
    <td>
        <a href="print.php?id=<?php echo urlencode($row['cid']); ?>" target="_blank">
            <button class="btn btn-primary"><i class="fa fa-print"></i> Print</button>
        </a>
    </td>
</tr>
<?php
        $cnt++;
    }
} else {
    echo "<tr><td colspan='8' style='text-align:center;'>No enrollment history found.</td></tr>";
}
$stmt->close();
?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div> <!-- /.container -->
</div> <!-- /.content-wrapper -->

<?php include('includes/footer.php'); ?>

<!-- JAVASCRIPT FILES -->
<script src="assets/js/jquery-1.11.1.js"></script>
<script src="assets/js/bootstrap.js"></script>

</body>
</html>
