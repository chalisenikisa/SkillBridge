<?php
session_start();
include('includes/config.php'); // Database connection

if (isset($_POST['login'])) {  // Check if login form is submitted
    $regno = $_POST['regno'];
    $password = $_POST['password'];

    // Fetch student data from database
    $query = mysqli_query($con, "SELECT * FROM students WHERE StudentRegno='$regno'");
    $row = mysqli_fetch_array($query);

    if ($row) {
        //  Correct place to use password_verify
        if (password_verify($password, $row['password'])) {
            // Password correct — login successful
            $_SESSION['slogin'] = $regno;
            $_SESSION['student_id'] = $row['id'];

            header("Location: student-index.php"); // Redirect to dashboard
            exit();
        } else {
            // Password wrong
            echo "<script>alert('Invalid Password!');</script>";
        }
    } else {
        // Registration number not found
        echo "<script>alert('Student Registration Number not found!');</script>";
    }
}
?>

<form method="POST" action="">
    <div class="form-group">
        <label for="regno">Registration Number</label>
        <input type="text" name="regno" id="regno" required class="form-control" placeholder="Enter Registration Number">
    </div>

    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" required class="form-control" placeholder="Enter Password">
    </div>

    <button type="submit" name="login" class="btn btn-primary">Login</button>
</form>
