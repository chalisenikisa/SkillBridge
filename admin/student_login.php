<?php
session_start();
include("includes/config.php");

if (isset($_POST['login'])) {
    $regno = $_POST['regno'];
    $password = $_POST['password'];

    $query = mysqli_query($con, "SELECT * FROM students WHERE StudentRegno='$regno'");
    $student = mysqli_fetch_array($query);

    if ($student && password_verify($password, $student['password'])) {
        $_SESSION['login'] = $student['StudentRegno'];
        $_SESSION['sname'] = $student['studentName'];
        header("Location: index.php"); // Redirect to student dashboard
        exit();
    } else {
        $error = "Invalid Registration No. or Password!";
    }
}
?>
<!-- Student Login HTML Form -->
<form method="post" action="student-login.php">
    <input type="text" name="regno" placeholder="Registration No." required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit" name="login">Login</button>
</form>
<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
