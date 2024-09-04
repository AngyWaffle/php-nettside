<?php
include 'db.php'; // Ensure you have included your database connection setup
// Determine sorting order
$order_by = 'name ASC'; // Default sorting
if (isset($_GET['sort'])) {
    if ($_GET['sort'] == 'price_asc') {
        $order_by = 'price ASC';
    } elseif ($_GET['sort'] == 'price_desc') {
        $order_by = 'price DESC';
    } elseif ($_GET['sort'] == 'alpha_asc') {
        $order_by = 'name ASC';
    } elseif ($_GET['sort'] == 'alpha_desc') {
        $order_by = 'name DESC';
    }
}

/*function debug_to_console($data) {
    echo "<script>console.log('Debug Objects: " . $data . "' );</script>";
}*/

$query = "SELECT id, name, description, price, image_url FROM products ORDER BY $order_by";
$result = $conn->query($query);
//debug_to_console($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Products</title>
    <link rel="stylesheet" href="product_page.css">
    <link rel="stylesheet" href="topnav.css">
    <script src="burger.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <?php include 'topnav.php';?>
    <h1>Our Products</h1>
    <div class="sort-dropdown">
        <label for="sort">Sort by:</label>
        <select id="sort" name="sort" onchange="sortProducts()">
            <option value="alpha_asc" <?= isset($_GET['sort']) && $_GET['sort'] == 'alpha_asc' ? 'selected' : '' ?>>Alphabetical: A to Z</option>
            <option value="alpha_desc" <?= isset($_GET['sort']) && $_GET['sort'] == 'alpha_desc' ? 'selected' : '' ?>>Alphabetical: Z to A</option>
            <option value="price_asc" <?= isset($_GET['sort']) && $_GET['sort'] == 'price_asc' ? 'selected' : '' ?>>Price: Low to High</option>
            <option value="price_desc" <?= isset($_GET['sort']) && $_GET['sort'] == 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
        </select>
    </div>
    <div class="products-container">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="product-item">
                <a href="product.php?id=<?php echo $row['id']; ?>" style="color: inherit; text-decoration: none;">
                    <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                    <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                    <p><?php echo htmlspecialchars($row['description']); ?></p>
                    <p><strong>$<?php echo number_format($row['price'], 2); ?></strong></p>
                </a>
                <button onclick="addToCart(<?php echo $row['id']; ?>, 1)">Add to Cart</button>
            </div>
        <?php endwhile; ?>
    </div>
    <?php include 'footer.php';?>
    <script>
        function sortProducts() {
            var sort = document.getElementById('sort').value;
            window.location.href = 'product_page.php?sort=' + sort;
        }
        function addToCart(productId, quantity) {
        fetch('add_to_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `product_id=${productId}&quantity=${quantity}`
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();  // This line assumes the response is valid JSON
            })
            .then(data => {
                if(data.success) {
                    showNotification('Item added to cart!');
                }else{
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error adding item to cart:', error);
            });
        }
    </script>
</body>
</html>
<?php
    $conn->close();
    ?>