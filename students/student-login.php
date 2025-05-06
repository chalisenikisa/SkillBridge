<?php
session_start();
error_reporting(0);
include("includes/config.php");

if (isset($_POST['submit'])) {
    $regno = $_POST['regno'];
    $password = md5($_POST['password']);

    $query = mysqli_query($con, "SELECT * FROM students WHERE StudentRegno='$regno' AND password='$password'");
    $num = mysqli_fetch_array($query);

    if ($num > 0) {
        $_SESSION['login'] = $regno;
        $_SESSION['id'] = $num['studentRegno'];
        $_SESSION['sname'] = $num['studentName'];

        $uip = $_SERVER['REMOTE_ADDR'];
        $status = 1;
        mysqli_query($con, "INSERT INTO userlog(studentRegno, userip, status) VALUES('$regno', '$uip', '$status')");

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
    <title>Student Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
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
                    <li><a href="students/">Student Login</a></li>
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

        <span style="color:red;">
            <?php
            if (isset($_SESSION['errmsg'])) {
                echo htmlentities($_SESSION['errmsg']);
                $_SESSION['errmsg'] = ""; // Clear message
            }
            ?>
        </span>

        <div class="row">
            <div class="col-md-6">
                <form name="admin" method="post">
                    <label>Enter Reg No:</label>
                    <input type="text" name="regno" class="form-control" required />

                    <label>Enter Password:</label>
                    <input type="password" name="password" class="form-control" required />

                    <hr />
                    <button type="submit" name="submit" class="btn btn-info">
                        <span class="glyphicon glyphicon-user"></span> &nbsp;Log Me In
                    </button>
                </form>
            </div>

            <div class="col-md-6">
                <div class="alert alert-info">
                    <strong>Latest News / Updates</strong>
                    <marquee direction="up" scrollamount="2" onmouseover="this.stop();" onmouseout="this.start();">
                        <ul>
                            <?php
                            $sql = mysqli_query($con, "SELECT * FROM news ORDER BY postingDate DESC");
                            while ($row = mysqli_fetch_array($sql)) {
                            ?>
                                <li>
                                    <a href="news-details.php?nid=<?php echo htmlentities($row['id']); ?>">
                                        <?php echo htmlentities($row['newstitle']); ?> - <?php echo htmlentities($row['postingDate']); ?>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </marquee>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<!-- Scripts -->
<script src="assets/js/jquery-1.11.1.js"></script>
<script src="assets/js/bootstrap.js"></script>
</body>
</html>
