<?php
// Turn off error reporting if you don't want to display notices
error_reporting(0);

// The password you want to hash
$password = "123@name";

// Generate the hashed password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Output the result
echo "<h3>Hashed Password for '123@name':</h3>";
echo "<p><code>$hashedPassword</code></p>";
?>
