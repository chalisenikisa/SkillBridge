<?php
session_start();
error_reporting(0);
include("includes/config.php");

// Login logic
if (isset($_POST['submit'])) {
    $regno = $_POST['regno'];
    $password = $_POST['password'];

    // Get user details securely
    $stmt = $con->prepare("SELECT * FROM students WHERE StudentRegno = ?");
    $stmt->bind_param("s", $regno);
    $stmt->execute();
    $result = $stmt->get_result();
    $num = $result->fetch_assoc();

    if ($num && md5($password) == $num['password']) {  // Use password_verify() if using password_hash() instead
        $_SESSION['login'] = $regno;
        $_SESSION['id'] = $num['studentRegno'];
        $_SESSION['sname'] = $num['studentName'];
        $uip = $_SERVER['REMOTE_ADDR'];
        $status = 1;

        $logStmt = $con->prepare("INSERT INTO userlog (studentRegno, userip, status) VALUES (?, ?, ?)");
        $logStmt->bind_param("ssi", $regno, $uip, $status);
        $logStmt->execute();

        header("Location: change-password.php");
        exit();
    } else {
        $_SESSION['errmsg'] = "Invalid Reg no or Password";
        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Student Login | News Details</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
</head>
<body>
<?php include('includes/header.php'); ?>

<section class="menu-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="navbar-collapse collapse">
                    <ul id="menu-top" class="nav navbar-nav navbar-right">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="admin/">Admin Login</a></li>
                        <li><a href="index.php">Student Login</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="content-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4 class="page-head-line">News Details</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-info">
                    <?php
                    $nid = isset($_GET['nid']) ? intval($_GET['nid']) : 0;
                    $sql = $con->prepare("SELECT * FROM news WHERE id = ?");
                    $sql->bind_param("i", $nid);
                    $sql->execute();
                    $result = $sql->get_result();

                    if ($row = $result->fetch_assoc()) {
                    ?>
                        <h3><?= htmlentities($row['newstitle']) ?></h3>
                        <small><?= htmlentities($row['postingDate']) ?></small>
                        <hr />
                        <p><?= htmlentities($row['newsDescription']) ?></p>
                    <?php
                    } else {
                        echo "<p>No news found.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
<script src="assets/js/jquery-1.11.1.js"></script>
<script src="assets/js/bootstrap.js"></script>
</body>
</html>
