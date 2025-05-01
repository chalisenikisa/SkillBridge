<?php
session_start();
include("includes/config.php");

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = mysqli_query($con, "SELECT * FROM admin WHERE username='$username'");
    $admin = mysqli_fetch_array($query);

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['alogin'] = $admin['username'];
        header("Location: index.php"); // Redirect to admin dashboard
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>
<!-- Admin Login HTML Form -->
<form method="post" action="admin-login.php">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit" name="login">Login</button>
</form>
<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
