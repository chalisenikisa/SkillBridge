<?php
session_start();
error_reporting(0);
include("includes/config.php");

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password']; // This will now be the plain password entered by the admin

    // Query to fetch the admin by username
    $query = mysqli_query($con, "SELECT * FROM admin WHERE username='$username'");
    $num = mysqli_fetch_array($query);

    if ($num) {
        $storedHash = $num['password']; // The hashed password stored in the database
        
        // Use password_verify to check if the entered password matches the stored hash
        if (password_verify($password, $storedHash)) {
            // Password matched
            $_SESSION['alogin'] = $_POST['username'];
            $_SESSION['id'] = $num['id'];
            header("location: change-password.php");
            exit();
        } else {
            // Invalid password
            $_SESSION['errmsg'] = "Invalid username or password";
            header("location: index.php");
            exit();
        }
    } else {
        // No user found
        $_SESSION['errmsg'] = "Invalid username or password";
        header("location: index.php");
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

    <title>Admin Login</title>
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <link href="../assets/css/style.css" rel="stylesheet" />
</head>
<body>
    <?php include('includes/header.php');?>

    <section class="menu-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="navbar-collapse collapse ">
                        <ul id="menu-top" class="nav navbar-nav navbar-right">
                             <li><a href="../index.php">Home </a></li>
                             <li><a href="index.php">Admin Login </a></li>
                              <li><a href="../index.php">Student Login</a></li>
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
                    <h4 class="page-head-line">Please Login To Enter into Admin Panel </h4>
                </div>
            </div>

            <span style="color:red;"><?php echo htmlentities($_SESSION['errmsg']); ?><?php echo htmlentities($_SESSION['errmsg'] = ""); ?></span>

            <form name="admin" method="post">
                <div class="row">
                    <div class="col-md-6">
                        <label>Enter Username : </label>
                        <input type="text" name="username" class="form-control" required />

                        <label>Enter Password :  </label>
                        <input type="password" name="password" class="form-control" required />

                        <hr />
                        <button type="submit" name="submit" class="btn btn-info"><span class="glyphicon glyphicon-user"></span> &nbsp;Log Me In </button>&nbsp;
                    </div>
                </div>
            </form>

            <div class="col-md-6">
                <img src="../assets/img/admin.png" class="img-responsive" />
            </div>
        </div>
    </div>

    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php');?>

    <!-- FOOTER SECTION END-->
    <!-- JAVASCRIPT AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
    <!-- CORE JQUERY SCRIPTS -->
    <script src="../assets/js/jquery-1.11.1.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="../assets/js/bootstrap.js"></script>
</body>
</html>