<?php
session_start();
include('includes/config.php');
error_reporting(0);

if (strlen($_SESSION['login']) == 0) {   
    header('location:index.php');
    exit();
}

date_default_timezone_set('Asia/Kathmandu');
$currentTime = date('d-m-Y h:i:s A', time());

if (isset($_POST['submit'])) {
    $pincode = trim($_POST['pincode']);
    $regno = $_SESSION['login'];

    // Use prepared statement to prevent SQL injection
    $stmt = $con->prepare("SELECT * FROM students WHERE pincode = ? AND StudentRegno = ?");
    $stmt->bind_param("ss", $pincode, $regno);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['pcode'] = $pincode;
        header("location:enroll.php");
        exit();
    } else {
        echo "<script>
            alert('Error: Wrong Pincode. Please enter a valid pincode!');
            window.location.href='my-pincode-verification.php';
        </script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Pincode Verification</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body>
<?php include('includes/header.php'); ?>

<?php if ($_SESSION['login'] != "") {
    include('includes/menubar.php');
} ?>

<div class="content-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-head-line">Student Pincode Verification</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Pincode Verification
                    </div>

                    <div class="panel-body">
                        <form name="pincodeverify" method="post">
                            <div class="form-group">
                                <label for="pincode">Enter Pincode</label>
                                <input type="password" class="form-control" id="pincode" name="pincode" placeholder="Pincode" required />
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary">Verify</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<!-- JAVASCRIPT -->
<script src="assets/js/jquery-1.11.1.js"></script>
<script src="assets/js/bootstrap.js"></script>
</body>
</html>
