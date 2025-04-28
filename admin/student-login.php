<?php
session_start();
include('includes/config.php'); // Connect to database

if (isset($_POST['login'])) {  // When student presses "Login" button
    $regno = $_POST['regno'];
    $password = $_POST['password'];

    $query = mysqli_query($con, "SELECT * FROM students WHERE StudentRegno='$regno'");
    $row = mysqli_fetch_array($query);

    if ($row) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['slogin'] = $regno;
            $_SESSION['student_id'] = $row['id'];
            header("Location: index.php");
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
<html>
<head>
<title>Student Login</title>
</head>
<body>

<form method="post">
    <label>Student Registration Number:</label>
    <input type="text" name="regno" required><br><br>
    
    <label>Password:</label>
    <input type="password" name="password" required><br><br>
    
    <button type="submit" name="login">Login</button>
</form>

</body>
</html>

    