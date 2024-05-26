<?php
include '../mysqli_connect.php';
session_start();

global $dbc;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['place_order'])) {
    $deliveryOption = $_POST['delivery_option'] == 'delivery' ? 1 : 0;

    // Fetch the roomID for room number 400 if takeout is selected
    if ($deliveryOption == 0) {
        $stmt = $dbc->prepare("SELECT roomID FROM ROOM WHERE roomNumber = ?");
        $roomNumber = 400;
        $stmt->bind_param("i", $roomNumber);
        $stmt->execute();
        $stmt->bind_result($roomID);
        $stmt->fetch();
        $stmt->close();

        if (!$roomID) {
            die("Error: Room number 400 does not exist in the ROOM table.");
        }
    } else {
        $roomID = $_POST['room_id'];
    }

    $paymentMethod = $_POST['payment_method'] == 'card' ? 1 : 0;
    $totalPrice = $_POST['total_price'];

    $orderDetails = $_SESSION['order'] ?? [];
    if (empty($orderDetails)) {
        die("Error: No items in your cart.");
    }

    $customerID = 3;
    $staffID = 1;
    $orderDate = date('Y-m-d H:i:s');

    mysqli_begin_transaction($dbc);

    try {
        $stmt = $dbc->prepare("INSERT INTO ORDERS (status, orderDate, grade, review, customer, sdeliverID, stakeID, price, paymentMethod, deliveryOption) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $status = 0;
        $grade = 0;
        $review = '';
        $stmt->bind_param("isisiiidii", $status, $orderDate, $grade, $review, $customerID, $staffID, $staffID, $totalPrice, $paymentMethod, $deliveryOption);
        $stmt->execute();
        $orderID = $stmt->insert_id;
        $stmt->close();

        $_SESSION['current_order_id'] = $orderID;

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
        header('location: currentorder.php');
        exit();

    } catch (Exception $e) {
        // Rollback transaction
        mysqli_rollback($dbc);
        die("Error placing order: " . $e->getMessage());
    }
}
