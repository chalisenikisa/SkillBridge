<?php
include('includes/config.php');

// Fetch all students with their current plain-text passwords
$query = mysqli_query($con, "SELECT * FROM students");
while($row = mysqli_fetch_array($query)) {
    $id = $row['id'];
    $plainPassword = $row['password'];

    // Hash the old plain password
    $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

    // Update the password to hashed password
    mysqli_query($con, "UPDATE students SET password='$hashedPassword' WHERE id='$id'");
}

echo "Passwords updated successfully!";
?>
