

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="main_styling.css">
    <script src="burger.js"></script>
    <link rel="stylesheet" href="topnav.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <h1>Checkout Information</h1>
    <?php
    include 'db.php'; // Assuming your DB connection settings are in this file
    session_start();
    $user_id = $_SESSION['user_id'] ?? null;

    // Fetching shipping info
    $shipping_info = $conn->prepare("SELECT address, city, state, zip_code, country FROM shipping_addresses WHERE user_id = ?");
    $shipping_info->bind_param("i", $user_id);
    $shipping_info->execute();
    $shipping_result = $shipping_info->get_result();
    $shipping = $shipping_result->fetch_assoc();

    // Fetching contact info
    $contact_info = $conn->prepare("SELECT email, phone FROM contact_info WHERE user_id = ?");
    $contact_info->bind_param("i", $user_id);
    $contact_info->execute();
    $contact_result = $contact_info->get_result();
    $contact = $contact_result->fetch_assoc();
    $_SESSION['contact'] = $contact;

    // Fetching card info
    $card_info = $conn->prepare("SELECT card_number, expiry_date, cvv FROM card_info WHERE user_id = ?");
    $card_info->bind_param("i", $user_id);
    $card_info->execute();
    $card_result = $card_info->get_result();
    $card = $card_result->fetch_assoc();
    //$_SESSION[]
    ?>
    <form action="process_checkout.php" method="post">
        <h2>Shipping Address</h2>
        <input placeholder="address" name="address" required value="<?= htmlspecialchars($shipping['address'] ?? '') ?>">
        <input placeholder="city" type="text" name="city" required value="<?= htmlspecialchars($shipping['city'] ?? '') ?>">
        <input placeholder="state" type="text" name="state" required value="<?= htmlspecialchars($shipping['state'] ?? '') ?>">
        <input placeholder="zip code" type="text" name="zip_code" required value="<?= htmlspecialchars($shipping['zip_code'] ?? '') ?>">
        <input placeholder="country" type="text" name="country" required value="<?= htmlspecialchars($shipping['country'] ?? '') ?>">
        <input type="checkbox" name="save_address" id="save_address" checked>
        <label for="save_address">Save this address</label>

        <h2>Contact Info</h2>
        <input placeholder="email" type="email" name="email" required value="<?= htmlspecialchars($contact['email'] ?? '') ?>">
        <input placeholder="phone" type="text" name="phone" required value="<?= htmlspecialchars($contact['phone'] ?? '') ?>">
        <input type="checkbox" name="save_contact" id="save_contact" checked>
        <label for="save_contact">Save my contact info</label>

        <h2>Card Info</h2>
        <input placeholder="card number" type="text" name="card_number" required value="<?= htmlspecialchars($card['card_number'] ?? '') ?>">
        <input type="date" name="expiry_date" required value="<?= htmlspecialchars($card['expiry_date'] ?? '') ?>">
        <input placeholder="cvv" type="text" name="cvv" required value="<?= htmlspecialchars($card['cvv'] ?? '') ?>">
        <input type="checkbox" name="save_card" id="save_card" checked>
        <label for="save_card">Save my card info</label>

        <button type="submit">Submit Order</button>
    </form>
</body>
</html>
<?php
    $conn->close();
    ?>