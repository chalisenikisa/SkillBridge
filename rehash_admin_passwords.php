<?php
include("includes/config.php"); // Adjust path if needed

// Fetch all admins
$result = mysqli_query($con, "SELECT id, password FROM admin");

while ($row = mysqli_fetch_assoc($result)) {
    $adminId = $row['id'];
    $oldPassword = $row['password'];

    // Skip if already hashed using password_hash (i.e., it starts with "$2y$")
    if (strpos($oldPassword, '$2y$') === 0) {
        continue; // Already hashed
    }

    // Rehash the MD5 password into a secure format (NOT RECOMMENDED FOR PROD - use plain password ideally)
    // Here we simulate a rehash assuming you somehow know the plain password
    // REAL solution: ask admin to reset password

    // For demo purposes only — NOT secure
    // Assume all MD5 hashes were made from the password 'admin123'
    if (md5('admin123') === $oldPassword) {
        $newHashed = password_hash('admin123', PASSWORD_DEFAULT);
        mysqli_query($con, "UPDATE admin SET password='$newHashed' WHERE id=$adminId");
        echo "Rehashed admin ID $adminId<br>";
    }
}
echo "Rehashing complete.";
?>
