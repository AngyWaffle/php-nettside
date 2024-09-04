<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stride Spectra - Cart</title>
    <link rel="stylesheet" href="cart.css">
    <link rel="stylesheet" href="topnav.css">
    <script src="burger.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<?php include 'topnav.php';?>
    
<?php
session_start();
include 'db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle quantity update
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = max(1, intval($_POST['new_quantity']));  // Ensure at least 1 item

    $update_sql = "UPDATE cart_items SET quantity = ? WHERE user_id = ? AND product_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("iii", $new_quantity, $user_id, $product_id);
    $update_stmt->execute();

    header("Location: cart.php");
    exit;
}

// Handle item removal
if (isset($_POST['remove_item'])) {
    $product_id = $_POST['product_id'];
    $delete_sql = "DELETE FROM cart_items WHERE user_id = ? AND product_id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("ii", $user_id, $product_id);
    $delete_stmt->execute();

    header("Location: cart.php");
    exit;
}

// Fetch the user's cart items
$sql = "SELECT p.id, p.name, p.price, c.quantity FROM cart_items c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$_SESSION['cart']=$result;

echo '<h1>Your Shopping Cart</h1>';
if ($result->num_rows > 0) {
    $_SESSION['price'] = 0;
    while ($row = $result->fetch_assoc()) {
        $_SESSION['price'] = $_SESSION['price'] + ($row['price'] * $row['quantity']);
        echo '<form method="post" class="cart-item">';
        echo '<div><p><strong>' . htmlspecialchars($row['name']) . ' - Price per item: $' . number_format($row['price'], 2) . '</strong></p>';
        echo '<input type="hidden" name="product_id" value="' . $row['id'] . '">';
        echo '<input class="quantity" type="number" name="new_quantity" value="' . $row['quantity'] . '" min="1"></div>';
        echo '<button type="submit" name="remove_item">Remove Item</button>';
        echo '</form>';
    }
    echo '<button onclick="window.location.href=' ."'checkout.php'".'">Check Out</button>';
} else {
    echo '<p>Your cart is empty.</p>';
}
?>

<?php include 'footer.php';?>
</body>
</html>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const cartItems = document.querySelectorAll('.cart-item');
    cartItems.forEach(item => {
        item.querySelector('[name="new_quantity"]').addEventListener('change', function(e) {
            e.preventDefault();
            const productId = item.querySelector('[name="product_id"]').value;
            const quantity = item.querySelector('[name="new_quantity"]').value;

            fetch('cart_update.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `product_id=${productId}&quantity=${quantity}`
            })
            .then(response => response.json())
            .then(data => {
                showNotification(data.message);
            });
        });

        item.querySelector('[name="update_quantity"]').addEventListener('submit', function(e) {
            
        })

        item.querySelector('[name="remove_item"]').addEventListener('click', function(e) {
            e.preventDefault();
            const productId = item.querySelector('[name="product_id"]').value;

            fetch('cart_remove.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `product_id=${productId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    //document.querySelector(`#item-${productId}`).remove(); // Assume each item has an ID like "item-1"
                    showNotification('Item removed successfully');
                } else {
                    showNotification('Failed to remove item');
                }
                if (data.success) {
                    item.remove(); // Remove the item element from the page
                }
            });
        });
    });
});

function showNotification(message) {
    const notification = document.createElement('div');
    notification.className = 'notification';
    notification.textContent = message;
    document.body.appendChild(notification);
    notification.style.display = 'block';

    setTimeout(() => {
        notification.remove();
    }, 3000); // Notification disappears after 3 seconds
}
</script>
<?php
    $conn->close();
    ?>