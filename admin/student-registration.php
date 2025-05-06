<?php
session_start();
include('includes/config.php');

// Redirect if not logged in
if (!isset($_SESSION['alogin']) || strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit();
}

$msg = '';
$error = '';

// Handle form submission
if (isset($_POST['submit'])) {
    $studentname = trim($_POST['studentname']);
    $studentregno = trim($_POST['studentregno']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // hashed password
    $pincode = rand(100000, 999999); // 6-digit pincode

    // Use prepared statements
    $stmt = $con->prepare("INSERT INTO students(studentName, StudentRegno, password, pincode) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $studentname, $studentregno, $password, $pincode);

    if ($stmt->execute()) {
        $msg = "Student Registered Successfully. Pincode is <strong>$pincode</strong>";
    } else {
        $error = "Something went wrong. Please try again.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Admin | Student Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
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
            <?php include('includes/menubar.php'); ?>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <h1 class="page-head-line">Student Registration</h1>

            <!-- Messages -->
            <?php if ($msg): ?>
                <div class="alert alert-success text-center"><?php echo $msg; ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-danger text-center"><?php echo $error; ?></div>
            <?php endif; ?>

            <!-- Registration Form -->
            <div class="panel panel-default">
                <div class="panel-heading">Register Student</div>
                <div class="panel-body">
                    <form method="post" autocomplete="off">
                        <div class="form-group">
                            <label for="studentname">Student Name</label>
                            <input type="text" class="form-control" id="studentname" name="studentname" required />
                        </div>
                        <div class="form-group">
                            <label for="studentregno">Student Reg No</label>
                            <input type="text" class="form-control" id="studentregno" name="studentregno" placeholder="e.g. 2025CS001" onblur="checkAvailability()" required />
                            <span id="user-availability-status1" style="font-size:12px;"></span>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required />
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary">Register</button>
                    </form>
                </div>
            </div>
        </div> <!-- End Main -->
    </div> <!-- End Row -->
</div> <!-- End Container -->

<?php include('includes/footer.php'); ?>

<!-- JS Scripts -->
<script src="../assets/js/jquery-1.11.1.js"></script>
<script src="../assets/js/bootstrap.js"></script>
<script>
function checkAvailability() {
    var regno = $("#studentregno").val().trim();
    if (regno === "") {
        $("#user-availability-status1").html('');
        return;
    }
    $("#user-availability-status1").html('<i class="fa fa-spinner fa-spin"></i> Checking...');
    $.ajax({
        url: "check_availability.php",
        type: "POST",
        data: { regno: regno },
        success: function(data) {
            $("#user-availability-status1").html(data);
        },
        error: function () {
            $("#user-availability-status1").html('<span class="text-danger">Error checking availability</span>');
        }
    });
}
</script>

</body>
</html>
