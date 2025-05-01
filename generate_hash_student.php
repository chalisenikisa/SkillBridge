<?php
// Turn off error reporting if you don't want to display notices
error_reporting(0);

// The student password you want to hash
$password = "student@123"; // Replace this with the actual student password

// Generate the hashed password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Output the result
echo "<h3>Hashed Password for 'student@123':</h3>";
echo "<p><code>$hashedPassword</code></p>";
?>
