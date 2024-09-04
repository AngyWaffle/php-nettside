<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'db.php'; // Database connection file
    
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string(strtolower($_POST['email']));
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashing the password

    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";

    if ($conn->query($sql) === TRUE) {
        header("Location: login.php"); // Redirect to login page after successful registration
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
}
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Stride Spectra - Sign Up</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <form action="signup.php" method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Sign Up</button>
    </form>
</body>
</html>

