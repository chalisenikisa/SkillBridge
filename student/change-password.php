<?php
session_start();
include("includes/config.php");

// Redirect if admin is not logged in
if (!isset($_SESSION['alogin'])) {
    header('Location: index.php');
    exit();
}

// Handle password change
if (isset($_POST['submit'])) {
    $currentPassword = $_POST['currentpassword'];
    $newPassword = $_POST['newpassword'];
    $confirmPassword = $_POST['confirmpassword'];

    $query = mysqli_query($con, "SELECT * FROM admin WHERE username='" . $_SESSION['alogin'] . "'");
    $num = mysqli_fetch_array($query);

    if ($num) {
        $storedHash = $num['password'];

        if (password_verify($currentPassword, $storedHash)) {
            if ($newPassword == $confirmPassword) {
                $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $updateQuery = mysqli_query($con, "UPDATE admin SET password='$newHashedPassword' WHERE username='" . $_SESSION['alogin'] . "'");

                if ($updateQuery) {
                    $_SESSION['msg'] = "Password updated successfully!";
                    header("Location: logout.php"); // Log out after successful password change
                    exit();
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
        $_SESSION['errmsg'] = "No admin user found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student - Change Password</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    <link href="../assets/css/style.css" rel="stylesheet" />
</head>
<body>
<?php include('includes/header.php'); ?>

<div class="container">
    <div class="row">
        
        <div class="col-md-3">
        <nav>
            
            <ul class="nav nav-pills nav-stacked">
    
    
            <div class="sidebar" style="width: 220px; background-color: #f8f9fa; position: fixed; top: 0; bottom: 0; left: 0; overflow-y: auto; border-right: 1px solid #ddd; padding-top: 20px;">
    <a href="enroll.php" style="display: flex; align-items: center; padding: 12px 20px; color: #333; text-decoration: none;">
        <i class="fa fa-pencil-square-o" style="margin-right: 10px;"></i> Enroll for Course
    </a>
    <a href="enroll-history.php" style="display: flex; align-items: center; padding: 12px 20px; color: #333; text-decoration: none;">
        <i class="fa fa-history" style="margin-right: 10px;"></i> Enroll History
    </a>
    <a href="my-profile.php" style="display: flex; align-items: center; padding: 12px 20px; color: #333; text-decoration: none;">
        <i class="fa fa-user" style="margin-right: 10px;"></i> My Profile
    </a>
    <a href="change-password.php" style="display: flex; align-items: center; padding: 12px 20px; color: #333; text-decoration: none;">
        <i class="fa fa-lock" style="margin-right: 10px;"></i> Change Password
    </a>
    <a href="logout.php" style="display: flex; align-items: center; padding: 12px 20px; color: #333; text-decoration: none;">
        <i class="fa fa-sign-out" style="margin-right: 10px;"></i> Logout
    </a>
</div>


            </nav>
            
        </div>

        <!-- Main content -->
        <div class="col-md-9">
            <h4 class="page-head-line">Change Your Password</h4>

            <?php if (!empty($_SESSION['errmsg'])): ?>
                <div class="alert alert-danger"><?php echo htmlentities($_SESSION['errmsg']); unset($_SESSION['errmsg']); ?></div>
            <?php endif; ?>

            <?php if (!empty($_SESSION['msg'])): ?>
                <div class="alert alert-success"><?php echo htmlentities($_SESSION['msg']); unset($_SESSION['msg']); ?></div>
            <?php endif; ?>

            <form method="post" name="changePassword">
                <div class="form-group">
                    <label>Enter Current Password:</label>
                    <input type="password" name="currentpassword" class="form-control" required />
                </div>

                <div class="form-group">
                    <label>Enter New Password:</label>
                    <input type="password" name="newpassword" class="form-control" required />
                </div>

                <div class="form-group">
                    <label>Confirm New Password:</label>
                    <input type="password" name="confirmpassword" class="form-control" required />
                </div>

                <button type="submit" name="submit" class="btn btn-info">Update Password</button>
            </form>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
<script src="../assets/js/jquery-1.11.1.js"></script>
<script src="../assets/js/bootstrap.js"></script>
</body>
</html>
