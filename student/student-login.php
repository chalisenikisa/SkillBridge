<?php
session_start();
error_reporting(0);
include("includes/config.php");

if (isset($_POST['submit'])) {
    $regno = $_POST['regno'];
    $password = $_POST['password'];

    $query = $con->prepare("SELECT * FROM students WHERE StudentRegno = ?");
    $query->bind_param("s", $regno);
    $query->execute();
    $result = $query->get_result();
    $num = $result->fetch_assoc();

    if ($num && password_verify($password, $num['password'])) {
        $_SESSION['login'] = $regno;
        $_SESSION['id'] = $num['studentRegno'];
        $_SESSION['sname'] = $num['studentName'];

        $uip = $_SERVER['REMOTE_ADDR'];
        $status = 1;
        $log = $con->prepare("INSERT INTO userlog(studentRegno, userip, status) VALUES (?, ?, ?)");
        $log->bind_param("ssi", $_SESSION['login'], $uip, $status);
        $log->execute();

        header("Location: change-password.php");
        exit();
    } else {
        $_SESSION['errmsg'] = "Invalid Reg no or Password";
        header("Location: student");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Student Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    
    <style>
        body {
            padding-top: 60px;
        }
        .sidebar {
            width: 220px;
            background-color: #f8f9fa;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            overflow-y: auto;
            border-right: 1px solid #ddd;
            padding-top: 20px;
        }
        .sidebar a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #333;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #e9ecef;
        }
        .main-content {
            margin-left: 240px;
        }
    </style>
</head>
<body>

<?php include('includes/header.php'); ?>

<!-- Sidebar Start -->
<div class="sidebar">
    <a href="enroll.php"><i class="fa fa-pencil-square-o" style="margin-right: 10px;"></i>Enroll for Course</a>
    <a href="enroll-history.php"><i class="fa fa-history" style="margin-right: 10px;"></i>Enroll History</a>
    <a href="my-profile.php"><i class="fa fa-user" style="margin-right: 10px;"></i>My Profile</a>
    <a href="change-password.php"><i class="fa fa-lock" style="margin-right: 10px;"></i>Change Password</a>
    <a href="logout.php"><i class="fa fa-sign-out" style="margin-right: 10px;"></i>Logout</a>
</div>
<!-- Sidebar End -->

<!-- Main Content Start -->
<div class="container-fluid main-content">
    <div class="row">
        <div class="col-md-12">
            <h3>Please Login To Enter</h3>
            <span style="color:red;">
                <?php 
                    echo htmlentities($_SESSION['errmsg']);
                    $_SESSION['errmsg'] = "";
                ?>
            </span>
        </div>
    </div>

    <form method="post">
        <div class="row">
            <div class="col-md-6">
                <label>Enter Reg no:</label>
                <input type="text" name="regno" class="form-control" required />
                
                <label>Enter Password:</label>
                <input type="password" name="password" class="form-control" required />
                
                <br />
                <button type="submit" name="submit" class="btn btn-info">
                    <i class="glyphicon glyphicon-user"></i> Log Me In
                </button>
            </div>

            <div class="col-md-6">
                <div class="alert alert-info">
                    <strong>Latest News / Updates</strong>
                    <marquee direction='up' scrollamount="2" onmouseover="this.stop();" onmouseout="this.start();">
                        <ul>
                            <?php
                            $sql = mysqli_query($con, "SELECT * FROM news ORDER BY postingDate DESC");
                            while ($row = mysqli_fetch_array($sql)) {
                                echo '<li><a href="news-details.php?nid=' . htmlentities($row['id']) . '">'
                                     . htmlentities($row['newstitle']) . ' - '
                                     . htmlentities($row['postingDate']) . '</a></li>';
                            }
                            ?>
                        </ul>
                    </marquee>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- Main Content End -->

<?php include('includes/footer.php'); ?>

<!-- JS -->
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>
