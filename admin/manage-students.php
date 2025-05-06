<?php
session_start();
include('includes/config.php');
error_reporting(0);

if(strlen($_SESSION['alogin']) == 0) {   
    header('location:index.php');
    exit();
} else {

    // Deletion
    if (isset($_GET['del']) && isset($_GET['id'])) {
        $id = mysqli_real_escape_string($con, $_GET['id']);
        mysqli_query($con, "DELETE FROM students WHERE StudentRegno = '$id'");
        echo '<script>alert("Student Record Deleted Successfully!"); window.location.href="manage-students.php";</script>';
    }

    // Password Reset
    if (isset($_GET['pass']) && isset($_GET['id'])) {
        $id = mysqli_real_escape_string($con, $_GET['id']);
        $defaultPassword = "password";
        $newpass = password_hash($defaultPassword, PASSWORD_DEFAULT);
        mysqli_query($con, "UPDATE students SET password = '$newpass' WHERE StudentRegno = '$id'");
        echo '<script>alert("Password reset. New password is: password"); window.location.href="manage-students.php";</script>';
    }
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Admin | Manage Students</title>
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <link href="../assets/css/style.css" rel="stylesheet" />
</head>
<body>
<?php include('includes/header.php'); ?>
<?php if($_SESSION['alogin'] != "") 

<div class="content-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-head-line">Manage Students</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Student Records
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive table-bordered">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Reg No</th>
                                        <th>Student Name</th>
                                        <th>Pincode</th>
                                        <th>Reg Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
<?php
$sql = mysqli_query($con, "SELECT * FROM students");
$cnt = 1;
while($row = mysqli_fetch_array($sql)) {
?>
<tr>
    <td><?php echo $cnt; ?></td>
    <td><?php echo htmlentities($row['StudentRegno']); ?></td>
    <td><?php echo htmlentities($row['studentName']); ?></td>
    <td><?php echo htmlentities($row['pincode']); ?></td>
    <td><?php echo htmlentities($row['creationdate']); ?></td>
    <td>
        <a href="edit-student-profile.php?id=<?php echo $row['StudentRegno']; ?>">
            <button class="btn btn-primary"><i class="fa fa-edit"></i> Edit</button>
        </a>
        <a href="manage-students.php?id=<?php echo $row['StudentRegno']; ?>&del=delete" onClick="return confirm('Are you sure you want to delete?')">
            <button class="btn btn-danger">Delete</button>
        </a>
        <a href="manage-students.php?id=<?php echo $row['StudentRegno']; ?>&pass=update" onClick="return confirm('Are you sure you want to reset the password?')">
            <button class="btn btn-warning">Reset Password</button>
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
                </div> <!-- End panel -->
            </div> <!-- End col -->
        </div> <!-- End row -->
    </div> <!-- End container -->
</div> <!-- End content-wrapper -->

<?php include('includes/footer.php'); ?>

<!-- Scripts -->
<script src="../assets/js/jquery-1.11.1.js"></script>
<script src="../assets/js/bootstrap.js"></script>
</body>
</html>
<?php } ?>
