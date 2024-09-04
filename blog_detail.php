<?php
include 'db.php';

// Get the post ID from the URL
$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch the blog post
$stmt = $conn->prepare("SELECT title, content, author, created_at, heroimg FROM blog_posts WHERE id = ?");
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 3;

$stmt = $conn->prepare("SELECT id, title, content, heroimg, smallimg FROM blog_posts WHERE id <> $post_id ORDER BY created_at DESC LIMIT ? ");
$stmt->bind_param("i", $limit);
$stmt->execute();
$blog = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="burger.js"></script>
    <link rel="stylesheet" href="topnav.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stride Spectra - <?= htmlspecialchars($post['title']); ?></title>
</head>
<?php
include "topnav.php";
?>
<body>
    <div class="grid-blog">
        <div class="main-content">
            <h1><?= htmlspecialchars($post['title']); ?></h1>
            <p><strong>By <?= htmlspecialchars($post['author']); ?> on <?= date('F j, Y', strtotime($post['created_at'])); ?></strong></p>
            <img src="<?= htmlspecialchars($post['heroimg']); ?>" alt="Main banner"><br>
            <div class="cont"><?= nl2br($post['content']); ?></div>
        </div>
        <div class="side-page">
        <?php while ($row = $blog->fetch_assoc()): ?>
                <div class="sideart" onclick="loadPost(<?= $row['id']; ?>)">
                    <img src="<?= htmlspecialchars($row['smallimg']); ?>" alt="Blog Post">
                    <h3><?= htmlspecialchars($row['title']); ?></h3>
                    <p><?= substr(htmlspecialchars($row['content']), 0, 100) . '...'; ?></p> <!-- Display a snippet -->
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <?php
    include "footer.php";
    $conn->close();
    ?>
</body>
</html>
