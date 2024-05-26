<?php
function getUserOrderHistory($customerID) {
    global $dbc;
    $sql = "SELECT o.orderID, o.orderDate, o.price, o.status, o.deliveryOption, oi.quantity, oi.price as itemPrice, i.name as itemName
            FROM ORDERS o
            JOIN ORDER_ITEM oi ON o.orderID = oi.orderID
            JOIN ITEM i ON oi.itemID = i.itemID
            WHERE o.customer = ?
            ORDER BY o.orderDate DESC";
    $stmt = $dbc->prepare($sql);
    $stmt->bind_param("i", $customerID);
    $stmt->execute();
    $result = $stmt->get_result();
    $orderHistory = [];
    while ($row = $result->fetch_assoc()) {
        $orderID = $row['orderID'];
        if (!isset($orderHistory[$orderID])) {
            $orderHistory[$orderID] = [
                'orderID' => $row['orderID'],
                'orderDate' => $row['orderDate'],
                'price' => $row['price'],
                'status' => $row['status'],
                'deliveryOption' => $row['deliveryOption'],
                'items' => []
            ];
        }
        $orderHistory[$orderID]['items'][] = [
            'itemName' => $row['itemName'],
            'quantity' => $row['quantity'],
            'itemPrice' => $row['itemPrice']
        ];
    }
    $stmt->close();
    return $orderHistory;
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
        default: return 'Unknown';
    }
}

function displayOrderHistory($orderHistory) {
    $html = '<h2>Order History</h2>';
    foreach ($orderHistory as $order) {
        $html .= '<div class="order">';
        $html .= '<h3>Order ID: ' . htmlspecialchars($order['orderID']) . '</h3>';
        $html .= '<p>Order Date: ' . htmlspecialchars($order['orderDate']) . '</p>';
        $html .= '<p>Status: ' . htmlspecialchars(getStatusText($order['status'])) . '</p>';
        $html .= '<p>Total Price: $' . htmlspecialchars(number_format($order['price'], 2)) . '</p>';
        $html .= '<p>Delivery Option: ' . ($order['deliveryOption'] == 1 ? 'Delivery' : 'Takeout') . '</p>';
        $html .= '<h4>Items Ordered:</h4>';
        $html .= '<ul>';
        foreach ($order['items'] as $item) {
            $html .= '<li>' . htmlspecialchars($item['itemName']) . ' x' . $item['quantity'] . ' ($' . number_format($item['itemPrice'], 2) . ' each)</li>';
        }
        $html .= '</ul>';
        $html .= '</div>';
    }
    return $html;
}
?>
