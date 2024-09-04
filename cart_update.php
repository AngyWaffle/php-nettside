<?php
session_start();
include 'db.php';

$response = ['success' => false, 'message' => ''];

if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'User not logged in.';
    echo json_encode($response);
    exit;
}

if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = max(1, intval($_POST['quantity']));  // Ensure at least 1 item
    $user_id = $_SESSION['user_id'];

    // Update quantity in the database
    $sql = "UPDATE cart_items SET quantity = ? WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $quantity, $user_id, $product_id);
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Quantity updated.';
    } else {
        $response['message'] = 'Failed to update quantity.';
    }
    echo json_encode($response);
}
?>
    <?php
    $conn->close();
    ?>