<?php
session_start();
include('includes/config.php');

if (isset($_POST['login'])) {
    $regno = $_POST['regno'];
    $password = $_POST['password'];

    $query = mysqli_query($con, "SELECT * FROM students WHERE StudentRegno='$regno'");
    $row = mysqli_fetch_array($query);

    if ($row) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['slogin'] = $regno;
            $_SESSION['student_id'] = $row['id'];
            header("Location: student-dashboard.php");
            exit();
        } else {
            echo "<script>alert('Invalid Password!');</script>";
        }
    } else {
        echo "<script>alert('Student Registration Number not found!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Login</title>

    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-form {
            width: 400px;
            margin: 80px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px #0000001a;
        }
        .login-form h2 {
            margin-bottom: 30px;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="login-form">
    <h2>Student Login</h2>

    <form method="post">
        <div class="mb-3">
            <label class="form-label">Registration Number</label>
            <input type="text" class="form-control" name="regno" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" name="password" required>
        </div>

        <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
    </form>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
