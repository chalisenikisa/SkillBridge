<?php
session_start();
include('includes/config.php');

// Check if the admin is logged in
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit();
}

if (isset($_POST['submit'])) {
    // Sanitize form inputs
    $studentname = mysqli_real_escape_string($con, $_POST['studentname']);
    $studentregno = mysqli_real_escape_string($con, $_POST['studentregno']);
    $password = md5($_POST['password']); // Not secure for production, use password_hash() instead
    $pincode = rand(100000, 999999);  // Generate a 6-digit pincode

    // Insert into the database
    $query = "INSERT INTO students (studentName, StudentRegno, password, pincode) 
              VALUES ('$studentname', '$studentregno', '$password', '$pincode')";

    $ret = mysqli_query($con, $query);

    if ($ret) {
        $_SESSION['msg'] = "Student Registered Successfully. Pincode is: " . $pincode;
        header("Location: manage-students.php");
        exit();
    } else {
        $_SESSION['msg'] = "Something went wrong. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
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

                        <div style="margin: 10px 0; color: green; text-align: center;">
                            <?php
                            if (isset($_SESSION['msg']) && $_SESSION['msg'] != "") {
                                echo htmlentities($_SESSION['msg']);
                                $_SESSION['msg'] = ""; // clear the message
                            }
                            ?>
                        </div>

                        <div class="panel-body">
                            <form name="dept" method="post">
                                <div class="form-group">
                                    <label for="studentname">Student Name</label>
                                    <input type="text" class="form-control" id="studentname" name="studentname" placeholder="Student Name" required />
                                </div>

                                <div class="form-group">
                                    <label for="studentregno">Student Reg No</label>
                                    <input type="text" class="form-control" id="studentregno" name="studentregno" onBlur="userAvailability()" placeholder="Student Reg no" required />
                                    <span id="user-availability-status1" style="font-size:12px;"></span>
                                </div>

                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required />
                                </div>

                                <button type="submit" name="submit" class="btn btn-default">Submit</button>
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
        jQuery.ajax({
            url: "check_availability.php",
            data: 'regno=' + $("#studentregno").val(),
            type: "POST",
            success: function(data) {
                $("#user-availability-status1").html(data);
                $("#loaderIcon").hide();
            },
            error: function() {
                $("#user-availability-status1").html("Error checking availability.");
            }
        });
    }
    </script>
</body>
</html>
