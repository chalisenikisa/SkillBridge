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


