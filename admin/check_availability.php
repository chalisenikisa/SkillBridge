<?php 
require_once("includes/config.php");

if (!empty($_POST["regno"])) {
    $regno = trim($_POST["regno"]);

    // Prepared statement to prevent SQL injection
    $stmt = $con->prepare("SELECT StudentRegno FROM students WHERE StudentRegno = ?");
    $stmt->bind_param("s", $regno);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<span style='color:red;'>Student with this Reg No is already registered.</span>";
        echo "<script>document.getElementById('submit').disabled = true;</script>";
    } else {
        echo "<span style='color:green;'>Student Reg No available for registration.</span>";
        echo "<script>document.getElementById('submit').disabled = false;</script>";
    }

    $stmt->close();
}
?>
