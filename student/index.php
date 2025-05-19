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
        header("Location: index.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Student Login</title>
    

   <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <link href="../assets/css/style.css" rel="stylesheet" />


    
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

<script src="../assets/js/jquery-1.11.1.js"></script>
<script src="../assets/js/bootstrap.js"></script>
</body>
</html>
