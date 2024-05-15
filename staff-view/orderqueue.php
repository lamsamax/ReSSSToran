<?php
session_start();

// Initialize orders in session if not already set
if (!isset($_SESSION['orders'])) {
    $_SESSION['orders'] = [
        ['order_id' => 1, 'customer_name' => 'John Doe', 'order_items' => [['item' => 'Burger', 'quantity' => 2, 'price' => 5.00]], 'total_price' => 15.00, 'status' => 'Pending', 'queue' => 'new'],
        ['order_id' => 2, 'customer_name' => 'Jane Smith', 'order_items' => [['item' => 'Pizza', 'quantity' => 1, 'price' => 18.50]], 'total_price' => 18.50, 'status' => 'Pending', 'queue' => 'new']
    ];
}

function updateOrderStatus($orderId, $newStatus, $newQueue) {
    foreach ($_SESSION['orders'] as &$order) {
        if ($order['order_id'] == $orderId) {
            $order['status'] = $newStatus;
            $order['queue'] = $newQueue;
            break;
        }
    }
    // Make sure to re-save the updated array back to the session
    $_SESSION['orders'] = $_SESSION['orders'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    updateOrderStatus($_POST['order_id'], $_POST['status'], $_POST['queue']);
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

function displayOrders($orders, $queue) {
    $html = "<h2>" . ($queue === 'new' ? 'New Orders' : 'Orders Being Processed') . "</h2>";
    $html .= '<table>';
    $html .= '<tr><th>Order ID</th><th>Customer Name</th><th>Items Ordered</th><th>Total Price</th><th>Status</th><th>Actions</th></tr>';
    foreach ($orders as $order) {
        if ($order['queue'] === $queue) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($order['order_id']) . '</td>';
            $html .= '<td>' . htmlspecialchars($order['customer_name']) . '</td>';
            $items = array_map(function($item) {
                return htmlspecialchars($item['item']) . ' x' . $item['quantity'];
            }, $order['order_items']);
            $html .= '<td>' . implode(", ", $items) . '</td>';
            $html .= '<td>$' . htmlspecialchars(number_format($order['total_price'], 2)) . '</td>';
            $html .= '<td>' . htmlspecialchars($order['status']) . '</td>';
            $html .= '<td>';
            if ($queue === 'new') {
                $html .= '<form method="post"><input type="hidden" name="order_id" value="' . $order['order_id'] . '"><input type="hidden" name="status" value="Accepted"><input type="hidden" name="queue" value="processing"><button type="submit" class="accept">Accept</button></form>';
                $html .= '<form method="post"><input type="hidden" name="order_id" value="' . $order['order_id'] . '"><input type="hidden" name="status" value="Declined"><input type="hidden" name="queue" value="declined"><button type="submit" class="decline">Decline</button></form>';
            } else if ($queue === 'processing') {
                $html .= '<form method="post"><input type="hidden" name="order_id" value="' . $order['order_id'] . '"><input type="hidden" name="status" value="Delivered"><input type="hidden" name="queue" value="completed"><button type="submit" class="mark-done">Mark as Done</button></form>';
            }
            $html .= '</td>';
            $html .= '</tr>';
        }
    }
    $html .= '</table>';
    return $html;
}
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
<?php echo displayOrders($_SESSION['orders'], 'new'); ?>
<?php echo displayOrders($_SESSION['orders'], 'processing'); ?>
</div>
</body>
</html>
