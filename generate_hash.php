<?php
// Plain password to hash
$plainPassword = 'securePass123';

// Convert the plain password to a hash using password_hash() function
$hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

// Output the hashed password
echo 'Hashed Password: ' . $hashedPassword;
?>
