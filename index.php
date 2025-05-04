<?php
session_start();
error_reporting(0);
include("includes/config.php");

if (isset($_POST['submit'])) {
    $regno = $_POST['regno'];
    $password = $_POST['password'];

    // Use prepared statements to prevent SQL injection
    $stmt = $con->prepare("SELECT * FROM students WHERE StudentRegno = ?");
    $stmt->bind_param("s", $regno);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row && password_verify($password, $row['password'])) {
        // Authentication success
        $_SESSION['login'] = $regno;
        $_SESSION['id'] = $row['StudentRegno'];
        $_SESSION['sname'] = $row['studentName'];

        $uip = $_SERVER['REMOTE_ADDR'];
        $status = 1;
        $log_stmt = $con->prepare("INSERT INTO userlog(studentRegno, userip, status) VALUES(?, ?, ?)");
        $log_stmt->bind_param("ssi", $regno, $uip, $status);
        $log_stmt->execute();

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
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Student Login</title>
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
                            <li><a href="index.php" class="active">Student Login</a></li>
                            <li><a href="admin/">Admin Login</a></li>
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
                    <h4 class="page-head-line">Please Login To Enter</h4>
                </div>
            </div>

            <!-- Error message -->
            <?php if (!empty($_SESSION['errmsg'])): ?>
                <div class="alert alert-danger">
                    <?php 
                        echo htmlentities($_SESSION['errmsg']); 
                        $_SESSION['errmsg'] = ""; 
                    ?>
                </div>
            <?php endif; ?>

            <!-- Login form -->
            <form name="studentlogin" method="post" autocomplete="off">
                <div class="row">
                    <div class="col-md-6">
                        <label>Enter Reg no:</label>
                        <input type="text" name="regno" class="form-control" required />

                        <label>Enter Password:</label>
                        <input type="password" name="password" class="form-control" required />

                        <hr />
                        <button type="submit" name="submit" class="btn btn-info">
                            <span class="glyphicon glyphicon-user"></span> &nbsp;Log Me In
                        </button>
                    </div>
                </div>
            </form>

            <!-- News / Updates -->
            <div class="row" style="margin-top: 20px;">
                <div class="col-md-6">
                    <div class="alert alert-info">
                        <strong>Latest News / Updates</strong>
                        <marquee direction="up" scrollamount="2" onmouseover="this.stop();" onmouseout="this.start();">
                            <ul style="list-style: none; padding-left: 0;">
                                <?php
                                $sql = mysqli_query($con, "SELECT * FROM news ORDER BY postingDate DESC");
                                while ($news = mysqli_fetch_array($sql)) {
                                    echo '<li><a href="news-details.php?nid=' . htmlentities($news['id']) . '">'
                                        . htmlentities($news['newstitle']) . ' - '
                                        . htmlentities($news['postingDate']) . '</a></li>';
                                }
                                ?>
                            </ul>
                        </marquee>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php include('includes/footer.php , sidebar.php'); ?>
    <script src="assets/js/jquery-1.11.1.js"></script>
    <script src="assets/js/bootstrap.js"></script>
</body>
</html>
