<?php
// The plaintext password
$plaintextPassword = 'Admin-1234'; // The password you want to check

// Generate a new MD5 hash from the plaintext password
$newHash = md5($plaintextPassword);

// The original MD5 hash for comparison (replace this with your actual hash)
$hash = '58073af4306fa0d9827904ce8237a500'; // Example MD5 hash for 'password'

// Display the hashes
echo "Hash: $hash\n";
echo "New Hash: $newHash\n"; // This will output the new hash

// Verify the password against the original hash
if ($newHash === $hash) {
    echo "Password is valid!";
} else {
    echo "Invalid password.";
}
?>
