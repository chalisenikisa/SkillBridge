<?php
session_start();
include('includes/config.php');
error_reporting(0);

if (strlen($_SESSION['login']) == 0) {
    header('location:index.php');
    exit();
} else {
    date_default_timezone_set('Asia/Kathmandu');
    $currentTime = date('d-m-Y h:i:s A', time());

    if (isset($_POST['submit'])) {
        $regno = $_SESSION['login'];
        $currentpass = $_POST['cpass'];
        $newpass = $_POST['newpass'];
        $cnfpass = $_POST['cnfpass'];

        $sql = mysqli_query($con, "SELECT password FROM students WHERE studentRegno='$regno'");
        $row = mysqli_fetch_array($sql);

        if ($row && password_verify($currentpass, $row['password'])) {
            if ($newpass === $cnfpass) {
                $newHashedPass = password_hash($newpass, PASSWORD_DEFAULT);
                $update = mysqli_query($con, "UPDATE students SET password='$newHashedPass', updationDate='$currentTime' WHERE studentRegno='$regno'");

                $_SESSION['msg'] = $update ? "Password changed successfully!" : "Error updating password!";
            } else {
                $_SESSION['msg'] = "New and confirm passwords do not match!";
            }
        } else {
            $_SESSION['msg'] = "Current password is incorrect!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Student | Change Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <style>
        .sidebar {
            background-color: #f5f5f5;
            padding: 15px;
            border-right: 1px solid #ddd;
            height: 100%;
        }
        .sidebar a {
            display: block;
            padding: 8px 0;
            color: #333;
            text-decoration: none;
        }
        .sidebar a:hover {
            text-decoration: underline;
        }
    </style>

    <script type="text/javascript">
    function valid() {
        if (document.chngpwd.cpass.value == "") {
            alert("Current Password field is empty!");
            document.chngpwd.cpass.focus();
            return false;
        } else if (document.chngpwd.newpass.value == "") {
            alert("New Password field is empty!");
            document.chngpwd.newpass.focus();
            return false;
        } else if (document.chngpwd.cnfpass.value == "") {
            alert("Confirm Password field is empty!");
            document.chngpwd.cnfpass.focus();
            return false;
        } else if (document.chngpwd.newpass.value != document.chngpwd.cnfpass.value) {
            alert("Password and Confirm Password do not match!");
            document.chngpwd.cnfpass.focus();
            return false;
        }
        return true;
    }
    </script>
</head>
<body>
<?php include('includes/header.php'); ?>
<?php if ($_SESSION['login'] != "") include('includes/sidebar.php'); ?>

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                <div class="sidebar">
                    <h4>Student Menu</h4>
                    <a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a>
                    <a href="profile.php"><i class="fa fa-user"></i> Profile</a>
                    <a href="enroll-course.php"><i class="fa fa-book"></i> Enroll Courses</a>
                    <a href="my-courses.php"><i class="fa fa-list"></i> My Courses</a>
                    <a href="change-password.php"><i class="fa fa-lock"></i> Change Password</a>
                    <a href="logout.php"><i class="fa fa-sign-out"></i> Logout</a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <h1 class="page-head-line">Change Password</h1>

                <?php if (!empty($_SESSION['msg'])): ?>
                    <div class="alert alert-info"><?php echo htmlentities($_SESSION['msg']); unset($_SESSION['msg']); ?></div>
                <?php endif; ?>

                <div class="panel panel-default">
                    <div class="panel-heading">Change Password</div>
                    <div class="panel-body">
                        <form name="chngpwd" method="post" onsubmit="return valid();">
                            <div class="form-group">
                                <label for="cpass">Current Password</label>
                                <input type="password" class="form-control" name="cpass" id="cpass" placeholder="Current Password" />
                            </div>

                            <div class="form-group">
                                <label for="newpass">New Password</label>
                                <input type="password" class="form-control" name="newpass" id="newpass" placeholder="New Password" />
                            </div>

                            <div class="form-group">
                                <label for="cnfpass">Confirm Password</label>
                                <input type="password" class="form-control" name="cnfpass" id="cnfpass" placeholder="Confirm Password" />
                            </div>

                            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                            <hr />
                        </form>
                    </div>
                </div>
            </div>
        </div> <!-- /.row -->
    </div> <!-- /.container-fluid -->
</div> <!-- /.content-wrapper -->

<?php include('includes/footer.php'); ?>
<script src="assets/js/jquery-1.11.1.js"></script>
<script src="assets/js/bootstrap.js"></script>
</body>
</html>
