<?php 
session_start();
require_once("includes/config.php");

if (!empty($_POST["cid"])) {
    $cid = trim($_POST["cid"]);
    $regid = trim($_SESSION['login']);

    // 1. Check if already enrolled
    $query1 = "SELECT studentRegno FROM courseenrolls WHERE course = ? AND studentRegno = ?";
    $stmt1 = mysqli_prepare($con, $query1);
    mysqli_stmt_bind_param($stmt1, "ss", $cid, $regid);
    mysqli_stmt_execute($stmt1);
    mysqli_stmt_store_result($stmt1);

    if (mysqli_stmt_num_rows($stmt1) > 0) {
        echo "<span style='color:red'>Already applied for this course.</span>";
        echo "<script>$('#submit').prop('disabled',true);</script>";
        exit;
    }

    // 2. Check if seats are available
    $query2 = "SELECT COUNT(*) as totalEnrolled FROM courseenrolls WHERE course = ?";
    $stmt2 = mysqli_prepare($con, $query2);
    mysqli_stmt_bind_param($stmt2, "s", $cid);
    mysqli_stmt_execute($stmt2);
    $result2 = mysqli_stmt_get_result($stmt2);
    $row2 = mysqli_fetch_assoc($result2);
    $enrolled = $row2['totalEnrolled'];

    $query3 = "SELECT noofSeats FROM course WHERE id = ?";
    $stmt3 = mysqli_prepare($con, $query3);
    mysqli_stmt_bind_param($stmt3, "s", $cid);
    mysqli_stmt_execute($stmt3);
    $result3 = mysqli_stmt_get_result($stmt3);
    $row3 = mysqli_fetch_assoc($result3);
    $noofseat = $row3['noofSeats'];

    if ($enrolled >= $noofseat) {
        echo "<span style='color:red'>Seat not available for this course. All seats are full.</span>";
        echo "<script>$('#submit').prop('disabled',true);</script>";
    }
}
?>
