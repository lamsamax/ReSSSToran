<?php
include '../mysqli_connect.php';
session_start();

if (!isset($_SESSION['checkout']) || empty($_SESSION['checkout'])) {
    echo "No items in your cart.";
    exit;
}

$deliveryOption = $_SESSION['checkout']['deliveryOption'];
$paymentMethod = $_SESSION['checkout']['paymentMethod'];
$roomID = $_SESSION['checkout']['roomID'];
$orderDetails = $_SESSION['checkout']['orderDetails'];
$totalPrice = $_SESSION['checkout']['totalPrice'];
$customerID = 3; // Hardcoded customer ID for now
$staffID = 1; // Default staff ID

if (empty($orderDetails)) {
    die("Error: No items in your cart.");
}

// Start transaction
global $dbc;
mysqli_begin_transaction($dbc);

try {
    // Insert into ORDERS table
    $stmt = $dbc->prepare("INSERT INTO ORDERS (status, orderDate, grade, review, customer, sdeliverID, stakeID, price, paymentMethod, deliveryOption) VALUES (?, NOW(), ?, ?, ?, ?, ?, ?, ?, ?)");
    $status = 0; // Default status
    $grade = 0; // Default grade
    $review = ''; // Default review
    $stmt->bind_param("isisiidi", $status, $grade, $review, $customerID, $staffID, $staffID, $totalPrice, $paymentMethod, $deliveryOption);
    $stmt->execute();
    $orderID = $stmt->insert_id;
    $stmt->close();

    // Insert into ORDER_ITEM table
    foreach ($orderDetails as $item) {
        $quantity = 1; // Assuming quantity is always 1
        $stmt = $dbc->prepare("INSERT INTO ORDER_ITEM (orderID, itemID, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiid", $orderID, $item['itemID'], $quantity, $item['price']);
        $stmt->execute();
        $stmt->close();
    }

    // Insert into DELIVERY_ROOM table
    $stmt = $dbc->prepare("INSERT INTO DELIVERY_ROOM (orderID, roomID, scheduledtime) VALUES (?, ?, NOW())");
    $stmt->bind_param("ii", $orderID, $roomID);
    $stmt->execute();
    $stmt->close();

    // Commit transaction
    mysqli_commit($dbc);

    // Clear session
    unset($_SESSION['checkout']);
    unset($_SESSION['order']);

    echo "Order placed successfully!";
} catch (Exception $e) {
    // Rollback transaction
    mysqli_rollback($dbc);
    die("Error placing order: " . $e->getMessage());
}

mysqli_close($dbc);
?>
