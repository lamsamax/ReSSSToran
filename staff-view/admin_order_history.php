<?php
include "../authorization.php";
include "statsfunctions.php"; // Include the functions file
checkUserRole('admin');
global $dbc;

$allOrders = getAllOrders();

function displayAllOrders($allOrders) {
    $html = '<h2>All Orders</h2>';
    foreach ($allOrders as $order) {
        $html .= '<div class="order">';
        $html .= '<h3>Order ID: ' . htmlspecialchars($order['orderID']) . '</h3>';
        $html .= '<p>Order Date: ' . htmlspecialchars($order['orderDate']) . '</p>';
        $html .= '<p>Customer: ' . htmlspecialchars($order['customerName']) . ' ' . htmlspecialchars($order['customerSurname']) . '</p>';
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Orders</title>
    <link rel="stylesheet" href="order_history.css">
</head>
<body>
<div class="background-gradient"></div>
<div class="content">
    <?php echo displayAllOrders($allOrders); ?>
</div>
</body>
</html>
