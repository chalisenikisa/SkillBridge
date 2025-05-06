<?php
session_start();
error_reporting(0);
include("includes/config.php");

// Handle login
if (isset($_POST['submit'])) {
    $regno = $_POST['regno'];
    $password = $_POST['password'];

    $stmt = $con->prepare("SELECT * FROM students WHERE StudentRegno = ?");
    $stmt->bind_param("s", $regno);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row && password_verify($password, $row['password'])) {
        $_SESSION['login'] = $regno;
        $_SESSION['id'] = $row['StudentRegno'];
        $_SESSION['sname'] = $row['studentName'];

        // Log the login event
        $uip = $_SERVER['REMOTE_ADDR'];
        $status = 1;
        $log_stmt = $con->prepare("INSERT INTO userlog(studentRegno, userip, status) VALUES (?, ?, ?)");
        $log_stmt->bind_param("ssi", $regno, $uip, $status);
        $log_stmt->execute();

        header("Location: student-dashboard.php");
        exit();
    } else {
        $_SESSION['errmsg'] = "Invalid Registration Number or Password";
        header("Location: student-login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Student Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<?php include('includes/header.php'); ?>

<section class="menu-section">
    <div class="container">
        <ul class="nav navbar-nav navbar-right">
            <li><a href="student-login.php">Student Login</a></li>
            <li><a href="admin/">Admin Login</a></li>
        </ul>
    </div>
</section>

<div class="content-wrapper">
    <div class="container" style="margin-top: 40px;">
        <div class="row">
            <div class="col-md-12">
                <h4 class="page-head-line">Please Login to Continue</h4>
            </div>
        </div>

        <?php if (!empty($_SESSION['errmsg'])): ?>
            <div class="alert alert-danger">
                <?php 
                    echo htmlentities($_SESSION['errmsg']); 
                    $_SESSION['errmsg'] = ""; 
                ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="row">
                <div class="col-md-6">
                    <label>Registration Number</label>
                    <input type="text" name="regno" class="form-control" required />

                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required />

                    <br />
                    <button type="submit" name="submit" class="btn btn-primary">
                        <i class="fa fa-sign-in-alt"></i> Login
                    </button>
                </div>
            </div>
        </form>

        <!-- Optional News Section -->
        <div class="row" style="margin-top: 30px;">
            <div class="col-md-6">
                <div class="alert alert-info">
                    <strong>Latest News</strong>
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

<?php include('includes/footer.php'); ?>

<!-- JS -->
<script src="assets/js/jquery-1.11.1.js"></script>
<script src="assets/js/bootstrap.js"></script>
</body>
</html>
