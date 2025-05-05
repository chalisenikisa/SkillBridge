<?php
session_start();
include("includes/config.php");

// OPTIONAL: Enable detailed error reporting only during development
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

if (isset($_POST['submit'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Use prepared statement to prevent SQL injection
    $stmt = $con->prepare("SELECT id, password FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Check if user exists
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($admin_id, $storedHash);
        $stmt->fetch();

        if (password_verify($password, $storedHash)) {
            $_SESSION['alogin'] = $username;
            $_SESSION['id'] = $admin_id;
            header("Location: change-password.php");
            exit();
        } else {
            $_SESSION['errmsg'] = "Invalid username or password";
        }
    } else {
        $_SESSION['errmsg'] = "Invalid username or password";
    }

    $stmt->close();
    header("Location: index.php");
    exit();
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
                <div class="navbar-collapse collapse">
                    <ul id="menu-top" class="nav navbar-nav navbar-right">
                        <li><a href=""menu-top-active" href="index.php">Home</a></li>
                        <li><a class="menu-top-active" href="index.php">Admin Login</a></li>
                        <li><a href=""menu-top-active" href="index.php">Student Login</a></li>
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
                <h4 class="page-head-line">Please Login To Enter into Admin Panel</h4>
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

        <form name="admin" method="post">
            <div class="row">
                <div class="col-md-6">
                    <label>Enter Username:</label>
                    <input type="text" name="username" class="form-control" required />

                    <label>Enter Password:</label>
                    <input type="password" name="password" class="form-control" required />

                    <hr />
                    <button type="submit" name="submit" class="btn btn-info">
                        <span class="glyphicon glyphicon-user"></span> &nbsp;Log Me In
                    </button>
                </div>

                <div class="col-md-6">
                    <img src="../assets/img/admin.png" class="img-responsive" alt="Admin Login" />
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
