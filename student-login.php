<?php
session_start();
include('includes/config.php');

// Don't suppress errors entirely in development
// error_reporting(0); // Avoid this in production

if (isset($_POST['login'])) {
    $regno = trim($_POST['regno']);
    $enteredPassword = $_POST['password'];

    // Use prepared statements to prevent SQL injection
    $stmt = $con->prepare("SELECT * FROM students WHERE StudentRegno = ?");
    $stmt->bind_param("s", $regno);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $storedHash = $row['password'];

        if (password_verify($enteredPassword, $storedHash)) {
            session_regenerate_id(true); // Prevent session fixation
            $_SESSION['slogin'] = $regno;
            $_SESSION['student_id'] = $row['id'];
            header("Location: index.php");
            exit();
        } else {
            echo "<script>alert('Invalid password!');</script>";
        }
    } else {
        echo "<script>alert('Student registration number not found!');</script>";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Login</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }
        .login-box {
            max-width: 400px;
            margin: 80px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }
        .login-box h3 {
            margin-bottom: 25px;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h3 class="text-center">Student Login</h3>
        <form method="POST" action="">
            <div class="form-group">
                <label for="regno">Registration Number</label>
                <input type="text" name="regno" id="regno" required class="form-control" placeholder="Enter Registration Number">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required class="form-control" placeholder="Enter Password">
            </div>
            <button type="submit" name="login" class="btn btn-primary btn-block">Login</button>
        </form>
    </div>
</body>
</html>
