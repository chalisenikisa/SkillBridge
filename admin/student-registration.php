<?php
session_start();
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit();
}

if (isset($_POST['submit'])) {
    $studentname = trim($_POST['studentname']);
    $studentregno = trim($_POST['studentregno']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  // Secure hash
    $pincode = rand(100000, 999999); // 6-digit random pincode

    // Use prepared statement to prevent SQL injection
    $stmt = $con->prepare("INSERT INTO students(studentName, StudentRegno, password, pincode) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $studentname, $studentregno, $password, $pincode);
    $ret = $stmt->execute();
    $stmt->close();

    if ($ret) {
        echo "<script>alert('Student Registered Successfully. Pincode is $pincode'); window.location.href='manage-students.php';</script>";
        exit();
    } else {
        echo "<script>alert('Something went wrong. Please try again.'); window.location.href='manage-students.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Admin | Student Registration</title>
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <link href="../assets/css/style.css" rel="stylesheet" />
</head>
<body>

<?php include('includes/header.php'); ?>
<?php include('includes/menubar.php'); ?>

<div class="content-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-head-line">Student Registration</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">Student Registration</div>
                    <div class="panel-body">
                        <form method="post">
                            <div class="form-group">
                                <label for="studentname">Student Name</label>
                                <input type="text" class="form-control" id="studentname" name="studentname" placeholder="Student Name" required />
                            </div>
                            <div class="form-group">
                                <label for="studentregno">Student Reg No</label>
                                <input type="text" class="form-control" id="studentregno" name="studentregno" onblur="userAvailability()" placeholder="Student Reg No" required />
                                <span id="user-availability-status1" style="font-size:12px;"></span>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required />
                            </div>
                            <button type="submit" name="submit" id="submit" class="btn btn-primary">Register</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<script src="../assets/js/jquery-1.11.1.js"></script>
<script src="../assets/js/bootstrap.js"></script>
<script>
function userAvailability() {
    $("#loaderIcon").show();
    $.ajax({
        url: "check_availability.php",
        type: "POST",
        data: { regno: $("#studentregno").val() },
        success: function(data) {
            $("#user-availability-status1").html(data);
            $("#loaderIcon").hide();
        },
        error: function () {
            $("#user-availability-status1").html('<span style="color:red;">Error checking availability</span>');
            $("#loaderIcon").hide();
        }
    });
}
</script>
</body>
</html>
