<?php
session_start();
include 'db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

if (isset($_POST['product_id'])) {
    $product_id = (int)$_POST['product_id'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("DELETE FROM cart_items WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Item removed']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to remove item']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Product ID not provided']);
}
?>
    <?php
    $conn->close();
    ?>