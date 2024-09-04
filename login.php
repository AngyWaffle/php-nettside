<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'db.php'; // Database connection file

    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT id, password FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: index.php"); // Redirect to the main page
        } else {
            echo "Invalid password";
        }
    } else {
        echo "User not found";
    }
    $conn->close();
}
ini_set('display_errors', 1);
    error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Stride Spectra - Login</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <form action="login.php" method="post">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <a href="signup.php">Don't have an account? Sign up here</a>
        <button type="submit">Login</button>
    </form>
</body>
</html>
