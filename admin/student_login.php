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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-box {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        .login-box h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .login-box input[type="text"],
        .login-box input[type="password"] {
            width: 100%;
            padding: 12px 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
        }
        .login-box button {
            width: 100%;
            background: #007BFF;
            color: #fff;
            border: none;
            padding: 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }
        .login-box button:hover {
            background: #0056b3;
        }
        .error {
            color: red;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Student Login</h2>
    <form method="post" action="student-login.php">
        <input type="text" name="regno" placeholder="Registration No." required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>
    <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>
</div>

</body>
</html>