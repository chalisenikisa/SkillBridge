<?php
session_start();
include('includes/config.php'); // your database connection file

if (isset($_POST['login'])) {  // when user submits login form

    $regno = $_POST['regno'];  // Student registration number entered
    $enteredPassword = $_POST['password'];  // Password entered

    // Fetch student from database based on reg no
    $query = mysqli_query($con, "SELECT * FROM students WHERE StudentRegno='$regno'");
    $row = mysqli_fetch_array($query);

    if ($row) {
        $storedHash = $row['password'];  // Hashed password stored in DB

        // NOW, here is your code
        if (password_verify($enteredPassword, $storedHash)) {
            // Password correct
            $_SESSION['slogin'] = $regno;
            $_SESSION['student_id'] = $row['id'];

            header("Location: index.php"); // redirect to dashboard
            exit();
        } else {
            // Password incorrect
            echo "<script>alert('Invalid Password!');</script>";
        }
    } else {
        // Student registration number not found
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
