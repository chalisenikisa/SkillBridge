<?php
session_start();
require_once("includes/config.php");

if (!empty($_POST["cid"])) {
    $cid = $_POST["cid"];
    $regid = trim($_SESSION['login']); // Ensure no extra spaces

    // Check if student has already enrolled in the course
    $checkEnrollment = mysqli_query($con, "SELECT studentRegno FROM courseenrolls WHERE course='$cid' AND studentRegno='$regid'");
    $isEnrolled = mysqli_num_rows($checkEnrollment);

    if ($isEnrolled > 0) {
        echo "<span style='color:red'>Already applied for this course.</span>";
        echo "<script>$('#submit').prop('disabled',true);</script>";
        exit();
    }

    // Check if course has available seats
    $enrollmentCount = mysqli_query($con, "SELECT COUNT(*) as total FROM courseenrolls WHERE course='$cid'");
    $enrolled = mysqli_fetch_assoc($enrollmentCount)['total'];

    $seatQuery = mysqli_query($con, "SELECT noofSeats FROM course WHERE id='$cid'");
    $seatData = mysqli_fetch_assoc($seatQuery);
    $availableSeats = $seatData['noofSeats'];

    if ($enrolled >= $availableSeats) {
        echo "<span style='color:red'>Seat not available for this course. All seats are full.</span>";
        echo "<script>$('#submit').prop('disabled',true);</script>";
        exit();
    }
}
?>
