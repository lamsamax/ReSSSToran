<?php
global $order, $user_id, $orderStatus, $dbc;
session_start();
include '../proba.php';

// Include the existing current order file
include 'Location: ../../project-2024-group-4/php/currentorder.php'; // Replace with the actual path

// Assuming the existing file sets $order and $orderStatus
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="../CSS/profile.css">
    <title>Order Report</title>
</head>
<body>
<div class="report-box">
    <h1>Order Report for Order ID: <?php echo $order['orderID']; ?></h1>
    <p><strong>Customer ID:</strong> <?php echo $user_id; ?></p>
    <p><strong>Order Status:</strong> <?php echo $orderStatus; ?></p>
    <p><strong>Order Date:</strong> <?php echo $order['orderDate']; ?></p>

    <h2>Order Items</h2>
    <table>
        <thead>
        <tr>
            <th>Item ID</th>
            <th>Item Name</th>
            <th>Quantity</th>
            <th>Price</th>
        </tr>
        </thead>
        <tbody>
        <?php
        // Fetch the order items
        $orderItemsQuery = "SELECT * FROM ORDER_ITEM WHERE orderID='" . $order['orderID'] . "'";
        $orderItemsResult = mysqli_query($dbc, $orderItemsQuery);
        while ($item = mysqli_fetch_assoc($orderItemsResult)): ?>
            <tr>
                <td><?php echo $item['itemID']; ?></td>
                <td><?php echo $item['itemName']; ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td><?php echo $item['price']; ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
