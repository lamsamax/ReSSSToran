<?php
session_start();
include "../authorization.php" ;
checkUserRole('staff');
global $dbc;

function getOrders($status) {
    global $dbc;
    $sql = "SELECT o.orderID, o.customer, o.price, o.status, o.deliveryOption, c.name AS customerName, c.surname AS customerSurname, r.roomNumber
    FROM ORDERS o
    JOIN CUSTOMER c ON o.customer = c.customerID
    JOIN DELIVERY_ROOM dr ON o.orderID = dr.orderID
    JOIN ROOM r ON dr.roomID = r.roomID
    WHERE o.status = ?";
    $stmt = $dbc->prepare($sql);
    $stmt->bind_param("i", $status);
    $stmt->execute();
    $result = $stmt->get_result();
    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $row['items'] = getOrderItems($row['orderID']); // Fetch order items
        $orders[] = $row;
    }
    $stmt->close();
    return $orders;
}

function getStaffMembers() {
    global $dbc;
    $sql = "SELECT staffID, name FROM STAFF";
    $result = $dbc->query($sql);
    $staffMembers = [];
    while ($row = $result->fetch_assoc()) {
        $staffMembers[] = $row;
    }
    return $staffMembers;
}


function getOrderItems($orderId) {
    global $dbc;
    $sql = "SELECT oi.quantity, oi.price, i.name as itemName
            FROM ORDER_ITEM oi
            JOIN ITEM i ON oi.itemID = i.itemID
            WHERE oi.orderID = ?";
    $stmt = $dbc->prepare($sql);
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();
    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
    $stmt->close();
    return $items;
}

function updateOrderStatus($orderId, $newStatus, $staffID = null) {
    global $dbc;
    if ($newStatus == 1 && $staffID) { // Update stakeID when order is accepted
        $sql = "UPDATE ORDERS SET status = ?, stakeID = ? WHERE orderID = ?";
        $stmt = $dbc->prepare($sql);
        $stmt->bind_param("iii", $newStatus, $staffID, $orderId);
    } elseif ($newStatus == 4 && $staffID) { // Update sdeliverID when order is being delivered
        $sql = "UPDATE ORDERS SET status = ?, sdeliverID = ? WHERE orderID = ?";
        $stmt = $dbc->prepare($sql);
        $stmt->bind_param("iii", $newStatus, $staffID, $orderId);
    } else {
        $sql = "UPDATE ORDERS SET status = ? WHERE orderID = ?";
        $stmt = $dbc->prepare($sql);
        $stmt->bind_param("ii", $newStatus, $orderId);
    }
    $stmt->execute();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $staffID = null;
    if (isset($_POST['stakeID'])) {
        $staffID = $_POST['stakeID'];
    } elseif (isset($_POST['sdeliverID'])) {
        $staffID = $_POST['sdeliverID'];
    }
    updateOrderStatus($_POST['order_id'], $_POST['status'], $staffID);
    $_SESSION['current_order_id'] = $_POST['order_id']; // Set the current order ID in session
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}


function displayOrders($orders, $status) {
    $html = "<h2>" . getStatusTitle($status) . "</h2>";
    $html .= '<table>';
    $html .= '<tr><th>Order ID</th><th>Customer Name</th><th>Room Number</th><th>Items Ordered</th><th>Total Price</th><th>Status</th><th>Actions</th></tr>';
    $staffMembers = getStaffMembers();
    foreach ($orders as $order) {
        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($order['orderID']) . '</td>';
        $html .= '<td>' . htmlspecialchars($order['customerName']) . ' ' . htmlspecialchars($order['customerSurname']) . '</td>';
        $html .= '<td>' . htmlspecialchars($order['roomNumber']) . '</td>';
        $html .= '<td>';
        foreach ($order['items'] as $item) {
            $html .= htmlspecialchars($item['itemName']) . ' x' . $item['quantity'] . ' ($' . number_format($item['price'], 2) . ')<br>';
        }
        $html .= '</td>';
        $html .= '<td>$' . htmlspecialchars(number_format($order['price'], 2)) . '</td>';
        $html .= '<td>' . htmlspecialchars(getStatusText($order['status'])) . '</td>';
        $html .= '<td>';
        if ($status == 0) {
            $html .= '<form method="post"><input type="hidden" name="order_id" value="' . $order['orderID'] . '"><input type="hidden" name="status" value="1">';
            $html .= '<select name="stakeID">';
            foreach ($staffMembers as $staff) {
                $html .= '<option value="' . $staff['staffID'] . '">' . $staff['name'] . '</option>';
            }
            $html .= '</select>';
            $html .= '<button type="submit" class="accept">Accept</button></form>';
            $html .= '<form method="post"><input type="hidden" name="order_id" value="' . $order['orderID'] . '"><input type="hidden" name="status" value="6"><button type="submit" class="decline">Decline</button></form>';
        } elseif ($status == 1) {
            $html .= '<form method="post"><input type="hidden" name="order_id" value="' . $order['orderID'] . '"><input type="hidden" name="status" value="2"><button type="submit" class="in-making">In the Making</button></form>';
        } elseif ($status == 2) {
            $html .= '<form method="post"><input type="hidden" name="order_id" value="' . $order['orderID'] . '"><input type="hidden" name="status" value="3"><button type="submit" class="made">Made</button></form>';
        } elseif ($status == 3) {
            if ($order['deliveryOption'] == 1) { // 0 for takeout 1 for delivery
                $html .= '<form method="post"><input type="hidden" name="order_id" value="' . $order['orderID'] . '"><input type="hidden" name="status" value="4">';
                $html .= '<select name="sdeliverID">';
                foreach ($staffMembers as $staff) {
                    $html .= '<option value="' . $staff['staffID'] . '">' . $staff['name'] . '</option>';
                }
                $html .= '</select>';
                $html .= '<button type="submit" class="delivery">Delivering</button></form>';
            } else {
                $html .= '<form method="post"><input type="hidden" name="order_id" value="' . $order['orderID'] . '"><input type="hidden" name="status" value="5"><button type="submit" class="ready-takeout">Ready for Takeout</button></form>';
            }
        } elseif ($status == 4) {
            $html .= '<form method="post"><input type="hidden" name="order_id" value="' . $order['orderID'] . '"><input type="hidden" name="status" value="5"><button type="submit" class="delivered">Delivered</button></form>';
        }
        $html .= '</td>';
        $html .= '</tr>';
    }
    $html .= '</table>';
    return $html;
}


function getStatusText($status) {
    switch ($status) {
        case 0: return 'Sent';
        case 1: return 'Accepted';
        case 2: return 'In the Making';
        case 3: return 'Made';
        case 4: return 'Delivering';
        case 5: return 'Delivered';
        case 6: return 'Declined';
    }
}

function getStatusTitle($status) {
    switch ($status) {
        case 0: return 'New Orders';
        case 1: return 'Accepted Orders';
        case 2: return 'Orders in the Making';
        case 3: return 'Made Orders';
        case 4: return 'Orders Being Delivered';
        case 5: return 'Done Orders';
        case 6: return 'Declined Orders';
        default: return 'Orders';
    }
}

$newOrders = getOrders(0);
$acceptedOrders = getOrders(1);
$inMakingOrders = getOrders(2);
$madeOrders = getOrders(3);
$deliveringOrders = getOrders(4);
$doneOrders = getOrders(5);
$declinedOrders = getOrders(6);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management System</title>
    <link rel="stylesheet" href="queuestyle.css">
</head>
<body>
<div class="background-gradient"></div>
<div class="content">
    <?php echo displayOrders($newOrders, 0); ?>
    <?php echo displayOrders($acceptedOrders, 1); ?>
    <?php echo displayOrders($inMakingOrders, 2); ?>
    <?php echo displayOrders($madeOrders, 3); ?>
    <?php echo displayOrders($deliveringOrders, 4); ?>
    <?php echo displayOrders($doneOrders, 5); ?>
    <?php echo displayOrders($declinedOrders, 6); ?>

</div>
</body>
</html>
