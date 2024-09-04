<?php
session_start();
header('Content-Type: application/json'); // Set the correct header for JSON response
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please log in to add items to the cart.']);
    exit;
}

if (!isset($_POST['product_id'], $_POST['quantity'])) {
    echo json_encode(['success' => false, 'message' => 'Product data is incomplete.']);
    exit;
}

$product_id = (int)$_POST['product_id'];
$quantity = (int)$_POST['quantity'];
$user_id = $_SESSION['user_id'];

try {
    $stmt = $conn->prepare("SELECT quantity FROM cart_items WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $new_quantity = $row['quantity'] + $quantity;
        $stmt = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("iii", $new_quantity, $user_id, $product_id);
    } else {
        $stmt = $conn->prepare("INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $user_id, $product_id, $quantity);
    }

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Item added to cart successfully.']);
    } else {
        throw new Exception("Failed to add item to cart. SQL error: " . $stmt->error);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
<?php
$conn->close();
?>