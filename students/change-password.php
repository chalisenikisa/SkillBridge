<?php
session_start();
error_reporting(0);
include("includes/config.php");

if(isset($_POST['submit'])){
    $currentPassword = $_POST['currentpassword']; // Current password entered by user
    $newPassword = $_POST['newpassword']; // New password entered by user
    $confirmPassword = $_POST['confirmpassword']; // Confirm new password entered by user

    // Fetch the current password hash from the database for the logged-in user
    $query = mysqli_query($con, "SELECT * FROM admin WHERE username='".$_SESSION['alogin']."'");
    $num = mysqli_fetch_array($query);
    
    if($num) {
        $storedHash = $num['password']; // The current hashed password in the database
        
        // Check if the current password entered by the user matches the stored hashed password
        if(password_verify($currentPassword, $storedHash)) {
            // Check if the new password and confirm password match
            if($newPassword == $confirmPassword) {
                // Hash the new password
                $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                
                // Update the password in the database
                $updateQuery = mysqli_query($con, "UPDATE admin SET password='$newHashedPassword' WHERE username='".$_SESSION['alogin']."'");
                
                if($updateQuery) {
                    $_SESSION['msg'] = "Password updated successfully!";
                } else {
                    $_SESSION['errmsg'] = "Error updating password!";
                }
            } else {
                $_SESSION['errmsg'] = "New password and confirm password do not match!";
            }
        } else {
            $_SESSION['errmsg'] = "Current password is incorrect!";
        }
    } else {
        $_SESSION['errmsg'] = "No user found!";
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

    <title>Change Password</title>
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
                              <li><a href="students/index.php">Student Login</a></li>
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
                    <h4 class="page-head-line">Change Your Password</h4>
                </div>
            </div>
            <span style="color:red;"><?php echo htmlentities($_SESSION['errmsg']); ?><?php echo htmlentities($_SESSION['errmsg'] = ""); ?></span>
            <span style="color:green;"><?php echo htmlentities($_SESSION['msg']); ?><?php echo htmlentities($_SESSION['msg'] = ""); ?></span>
            
            <form name="changePassword" method="post">
                <div class="row">
                    <div class="col-md-6">
                        <label>Enter Current Password: </label>
                        <input type="password" name="currentpassword" class="form-control" required />
                        
                        <label>Enter New Password: </label>
                        <input type="password" name="newpassword" class="form-control" required />
                        
                        <label>Confirm New Password: </label>
                        <input type="password" name="confirmpassword" class="form-control" required />
                        
                        <hr />
                        <button type="submit" name="submit" class="btn btn-info">Update Password</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php include('includes/footer.php');?>

    <script src="../assets/js/jquery-1.11.1.js"></script>
    <script src="../assets/js/bootstrap.js"></script>
</body>
</html>
