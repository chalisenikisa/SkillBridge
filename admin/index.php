<?php
session_start();
include("includes/config.php");

// Clear previous error message
$_SESSION['errmsg'] = "";

// Handle login form submission
if (isset($_POST['submit'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Use prepared statements to prevent SQL injection
    $stmt = $con->prepare("SELECT id, password FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['alogin'] = $username;
        $_SESSION['id'] = $admin['id'];
        header("Location: change-password.php");
        exit();
    } else {
        $_SESSION['errmsg'] = "Invalid username or password";
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
    <title>Admin Login</title>
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <link href="../assets/css/style.css" rel="stylesheet" />
</head>
<body>

<?php include('includes/header.php'); ?>

<section class="menu-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <ul id="menu-top" class="nav navbar-nav navbar-right">
                     <li><a href="index.php" class="active">Admin Login</a></li>
                     <li><a href="homepage.php" class="active">Home</a></li>
                    
                </ul>
            </div>
        </div>
    </div>
</section>

<div class="content-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4 class="page-head-line">Please Login to Enter the Admin Panel</h4>
            </div>
        </div>

        <!-- Error Message -->
        <?php if (!empty($_SESSION['errmsg'])): ?>
            <div class="alert alert-danger"><?php echo htmlentities($_SESSION['errmsg']); ?></div>
        <?php endif; ?>

        <!-- Login Form -->
        <form method="post" name="admin">
            <div class="row">
                <div class="col-md-6">
                    <label>Enter Username:</label>
                    <input type="text" name="username" class="form-control" required />

                    <label>Enter Password:</label>
                    <input type="password" name="password" class="form-control" required />

                    <hr />
                    <button type="submit" name="submit" class="btn btn-info">
                        <span class="glyphicon glyphicon-user"></span> &nbsp; Log Me In
                    </button>
                </div>

                <div class="col-md-6">
                    <img src="../assets/img/admin.png" class="img-responsive" alt="Admin Login">
                </div>
            </div>
        </form>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<script src="../assets/js/jquery-1.11.1.js"></script>
<script src="../assets/js/bootstrap.js"></script>
</body>
</html>
