<?php
session_start();
include 'db.php';  // Include your database connection file

// Get product ID from URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch product details from the database
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    die('Product not found!');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?></title>
    <link rel="stylesheet" href="product_styles.css">
    <link rel="stylesheet" href="topnav.css">
    <script src="burger.js"></script>
</head>
<body>
<?php include 'topnav.php';?>
    <main>
        <div class="product-gallery">
            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            <!-- Additional smaller images could be added here -->
        </div>
        <div class="product-details">
            <h1><?php echo htmlspecialchars($product['name']); ?></h1>
            <p><?php echo nl2br(htmlspecialchars($product['longdesc'])); ?></p>
            <p class="price">$<?php echo number_format($product['price'], 2); ?></p>
            <p id="produkt_key" class="no"><?php echo $product_id;?></p>
            <input type="number" name="quantity" value="1" min="1" id="quantity">
            <button onclick="addToCart(document.getElementById('produkt_key').innerHTML, document.getElementById('quantity').value)">Add to Cart</button>
            <div class="testimonial">
                <p><?php echo nl2br(htmlspecialchars($product['review'])); ?></p>
                <cite>- <?php echo nl2br(htmlspecialchars($product['revname'])); ?></cite>
            </div>
        </div>
    </main>

    <div id="notificationPopup" class="notification-popup">Item added to cart!</div>

    <?php include 'footer.php';?>
    <script>
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
                    failNotification(data.message);
                }
            })
            .catch(error => {
                console.error('Error adding item to cart:', error);
            });
        }

        /* function addToCart(event) {
            event.preventDefault(); // Prevent the default form submission
            let productId = event.target.closest('.product-item').getAttribute('data-product-id');
            let quantity = 1; // Default quantity

            fetch('add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `product_id=${productId}&quantity=${quantity}`
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message); // Simple alert for feedback
            })
            .catch(error => console.error('Error adding item to cart:', error));
        } */

    </script>
</body>
</html>
<?php
    $conn->close();
    ?>