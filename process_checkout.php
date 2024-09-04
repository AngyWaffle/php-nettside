<?php
session_start();
include 'db.php';

// Assume user is logged in and user_id is in session
$user_id = $_SESSION['user_id'];
$price = $_SESSION['price'];

// Insert into shipping_addresses
if (isset($_POST['save_address']) && $_POST['save_address'] == 'on') {
    $stmt = $conn->prepare("INSERT INTO shipping_addresses (user_id, address, city, state, zip_code, country) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $user_id, $_POST['address'], $_POST['city'], $_POST['state'], $_POST['zip_code'], $_POST['country']);
    $stmt->execute();
    unset($stmt);
}

// Insert into contact_info
if (isset($_POST['save_contact']) && $_POST['save_contact'] == 'on') {
    $stmt = $conn->prepare("INSERT INTO contact_info (user_id, email, phone) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $_POST['email'], $_POST['phone']);
    $stmt->execute();
    unset($stmt);
}

// Insert into card_info
if (isset($_POST['save_card']) && $_POST['save_card'] == 'on') {
    $stmt = $conn->prepare("INSERT INTO card_info (user_id, card_number, expiry_date, cvv) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $_POST['card_number'], $_POST['expiry_date'], $_POST['cvv']);
    $stmt->execute();
    unset($stmt);
}

// Create an order
$stmt = $conn->prepare("INSERT INTO orders (user_id, total) VALUES (?, ?)");
$total = $price; // Calculate total based on cart contents
$stmt->bind_param("id", $user_id, $total);
$stmt->execute();
$order_id = $stmt->insert_id;
unset($stmt);

// Insert order items (from session or cart)
foreach ($_SESSION['cart']->row as $product_id => $quantity) {
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $order_id, $product_id, $quantity);
    $stmt->execute();
    unset($stmt);
}

// Redirect or inform the user
alert("Order placed successfully!");
header("Location: index.php");
?>
    <?php
    $conn->close();
    ?>