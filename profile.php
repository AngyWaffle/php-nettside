<?php
session_start();
include 'db.php';  // Ensure you have included your database connection setup

$user_id = $_SESSION['user_id'];

$sql = "SELECT username FROM users WHERE id = $user_id";
$name = $conn->query($sql);

$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 2;

$stmt = $conn->prepare("SELECT id, title, content, smallimg FROM blog_posts ORDER BY created_at DESC LIMIT ?");
$stmt->bind_param("i", $limit);
$stmt->execute();
$blog = $stmt->get_result();
unset($stmt);
unset($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stride Spectra - <?= $name; ?></title>
    <script src="burger.js"></script>
    <link rel="stylesheet" href="topnav.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <?php include "topnav.php"; ?>
    <?php include "footer.php"; ?>
    <?php $conn->close();?>
</body>
</html>