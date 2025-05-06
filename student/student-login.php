<?php
session_start();
error_reporting(0);
include("includes/config.php");

if(isset($_POST['submit'])) {
    $regno = $_POST['regno'];
    $password = $_POST['password'];

    // Use prepared statement to avoid SQL injection
    $query = $con->prepare("SELECT * FROM students WHERE StudentRegno = ?");
    $query->bind_param("s", $regno); // 's' means string
    $query->execute();
    $result = $query->get_result();
    $num = $result->fetch_assoc();

    if($num) {
        // Verify password using password_verify
        if(password_verify($password, $num['password'])) {
            $_SESSION['login'] = $_POST['regno'];
            $_SESSION['id'] = $num['studentRegno'];
            $_SESSION['sname'] = $num['studentName'];

            // Log the user in
            $uip = $_SERVER['REMOTE_ADDR'];
            $status = 1;
            $log = $con->prepare("INSERT INTO userlog(studentRegno, userip, status) VALUES (?, ?, ?)");
            $log->bind_param("ssi", $_SESSION['login'], $uip, $status);
            $log->execute();

            // Redirect to change-password.php
            header("Location: change-password.php");
            exit();
        } else {
            $_SESSION['errmsg'] = "Invalid Reg no or Password";
            header("Location: index.php");
            exit();
        }
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
    <meta name="description" content="" />
    <meta name="author" content="" />
    
    <title>Student Login</title>
    
    <!-- Bootstrap CSS from CDN -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" />
    
    <!-- Font Awesome CSS from CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />

    <!-- Optional: Add your own custom CSS file -->
    <link href="path/to/style.css" rel="stylesheet" />

</head>
<body>
    <?php include('includes/header.php');?>

    <section class="menu-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="navbar-collapse collapse">
                        <ul id="menu-top" class="nav navbar-nav navbar-right">
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
                    <h4 class="page-head-line">Please Login To Enter</h4>
                </div>
            </div>
            
            <!-- Display error message if exists -->
            <span style="color:red;">
                <?php 
                    echo htmlentities($_SESSION['errmsg']);
                    $_SESSION['errmsg'] = ""; // Clear the error after showing it
                ?>
            </span>

            <form name="admin" method="post">
                <div class="row">
                    <div class="col-md-6">
                        <label>Enter Reg no: </label>
                        <input type="text" name="regno" class="form-control" required />
                        
                        <label>Enter Password: </label>
                        <input type="password" name="password" class="form-control" required />
                        
                        <hr />
                        
                        <button type="submit" name="submit" class="btn btn-info">
                            <span class="glyphicon glyphicon-user"></span> &nbsp;Log Me In 
                        </button>&nbsp;
                    </div>
                </div>
            </form>

            <div class="col-md-6">
                <div class="alert alert-info">
                    <strong>Latest News / Updates</strong>
                    <marquee direction='up' scrollamount="2" onmouseover="this.stop();" onmouseout="this.start();">
                        <ul>
                            <?php
                                $sql = mysqli_query($con, "SELECT * FROM news");
                                while($row = mysqli_fetch_array($sql)) {
                                    echo '<li><a href="news-details.php?nid=' . htmlentities($row['id']) . '">'
                                        . htmlentities($row['newstitle']) . ' - ' . htmlentities($row['postingDate']) . '</a></li>';
                                }
                            ?>
                        </ul>
                    </marquee>
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER SECTION END-->
    <?php include('includes/footer.php');?>

    <!-- JAVASCRIPT AT THE BOTTOM TO REDUCE THE LOADING TIME -->
    <!-- CORE JQUERY SCRIPTS -->
    <script src="assets/js/jquery-1.11.1.js"></script>
    <!-- BOOTSTRAP SCRIPTS -->
    <script src="assets/js/bootstrap.min.js"></script>
</body>
</html>
