<?php
session_start();
include 'db.php';  // Ensure you have included your database connection setup

$sql = "SELECT id, name, price, image_url FROM products";
$products = $conn->query($sql);

$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 2;

$stmt = $conn->prepare("SELECT id, title, content, smallimg FROM blog_posts ORDER BY created_at DESC LIMIT ?");
$stmt->bind_param("i", $limit);
$stmt->execute();
$blog = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stride Spectra</title>
    <link rel="stylesheet" href="main_styling.css">
    <script src="burger.js"></script>
    <link rel="stylesheet" href="topnav.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<?php include 'topnav.php';?>
    <section class="hero">
        <img src="images/heroeimg.png" alt="Main banner">
        <!-- <h1>Stride Spectra</h1> -->
    </section>
    <section class="blog-posts">
        <h2>Learn About Cheese</h2>
        <div class="post-grid">
            <?php while ($row = $blog->fetch_assoc()): ?>
                <div class="article" onclick="loadPost(<?= $row['id']; ?>)">
                    <img src="<?= htmlspecialchars($row['smallimg']); ?>" alt="Blog Post">
                    <h3><?= htmlspecialchars($row['title']); ?></h3>
                    <p><?= substr(htmlspecialchars($row['content']), 0, 100) . '...'; ?></p> <!-- Display a snippet -->
                </div>
            <?php endwhile; ?>
        </div>
    </section>
    <section class="products">
        <h2>Featured Products</h2>
        <div class="product-grid">
            <?php
            $i = 0;
            if ($products->num_rows > 0) {
                while ($row = $products->fetch_assoc()) {
                    echo '<div class="product"><a href="product.php?id=' . $row['id'] . '" class="product-link">';
                    echo '<img src="' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['name']) . '">';
                    echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
                    echo '<p>$' . number_format($row['price'], 2) . '</p>';
                    echo '</a></div>';
                    $i = $i+1;
                    if ($i == 3) {
                        break;
                    }
                }
            } else {
                echo '<p>No products found.</p>';
            }
            unset($row);
            ?>
        </div>
    </section>
    <section class="testimonials">
        <h2>What Our Customers Say</h2>
        <div class="testimonial-grid">
            <div class="testimonial">
                <p>"Alpine Breeze is a masterpiece among firm cheeses. Its nutty flavor is reminiscent of the fresh, alpine meadows where the cows graze, providing a rich and aromatic profile that delights the palate. The texture is perfectly balanced, offering a satisfying firmness that still melts smoothly in the mouth. Whether you're adding it to a sophisticated cheese board or pairing it with a crisp white wine, Alpine Breeze never fails to impress. It's also versatile in the kitchen, enhancing dishes from simple sandwiches to gourmet entrees with its robust and earthy tones. Truly, this cheese is a testament to the quality and tradition of alpine dairy craftsmanship."</p>
                <cite>- Alexander James Thornton</cite>
            </div>
            <div class="testimonial">
                <p>"Blue Moon stands out as an exceptional blue cheese, perfect for those who relish bold flavors. Its pungent aroma immediately captures the essence of a classic blue, while the taste delivers a tangy, creamy explosion that leaves a memorable impression. The cheese is soft yet holds its structure well, making it ideal for spreading on crackers or crumbling over a salad. Its complex flavor profile combines hints of sharpness with a smooth, almost buttery finish. Blue Moon is particularly delightful when paired with sweet accompaniments like honey or figs, balancing its intensity with natural sweetness. This cheese is a gourmet experience that elevates any culinary creation it's added to."</p>
                <cite>- Isabella Grace Hamilton</cite>
            </div>
        </div>
    </section>
    <section class="newsletter">
        <h2>Subscribe to Our Newsletter</h2>
        <form action="" method="post">
            <input type="email" name="email" placeholder="Enter your email">
            <button type="submit" disabled>Subscribe</button>
        </form>
    </section>
    <?php include 'footer.php';?>
    <?php
    $conn->close();
    ?>
</body>
</html>

