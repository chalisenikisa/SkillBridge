<?php
session_start();
include('includes/config.php');

if (isset($_POST['regno']) && isset($_POST['password'])) {
    $regno = $_POST['regno'];              // Student Registration Number entered
    $password = $_POST['password'];         // Password entered by student

    // 1. Fetch student record based on Registration Number
    $query = mysqli_query($con, "SELECT * FROM students WHERE StudentRegno='$regno'");
    $row = mysqli_fetch_array($query);

    if ($row) {
        // 2. Use password_verify() to compare entered password with hashed password from database
        if (password_verify($password, $row['123@name'])) {
            // 3. Password is correct → Log the student in
            $_SESSION['slogin'] = $regno;
            $_SESSION['student_id'] = $row['id'];
            header("Location: index.php");  // Redirect to dashboard
            exit();
        } else {
            // Wrong password
            echo "<script>alert('Invalid password. Please try again.');</script>";
        }
    } else {
        // No student found with that Registration Number
        echo "<script>alert('Student Registration Number not found.');</script>";
    }
}
?>
