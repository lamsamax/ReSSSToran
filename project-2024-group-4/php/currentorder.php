<?php
session_start();
include '../mysqli_connect.php';

global $dbc;

$customerID = 3; // Hardcoded customer ID for now
$orderQuery = "SELECT * FROM ORDERS WHERE customer = ? ORDER BY orderID DESC LIMIT 1"; // gets the latest user order
$stmt = $dbc->prepare($orderQuery);
$stmt->bind_param("i", $customerID);
$stmt->execute();
$orderResult = $stmt->get_result();
$order = $orderResult->fetch_assoc();

if (!$order) {
    echo "No orders found for this user.";
    exit;
}

$orderStatus = $order['status'];

$statusLabels = [
    0 => 'sent',
    1 => 'accepted',
    2 => 'inMaking',
    3 => 'done',
    4 => 'inDelivery',
    5 => 'delivered'
];

$stmt->close();
$dbc->close();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="../css/currentorderstyle.css">
    <title>Current Order</title>
</head>
<body>
<nav>
    <ul>
        <li><a href="customerhome.php">Home</a></li>
        <li><a href="menu.php">Menu</a></li>
        <li><a href="gallery.html">Gallery</a></li>
        <li><a href="contact.html">Contact</a></li>
        <li><a href="profile.html">Profile</a></li>
    </ul>
</nav>

<section class="order-status">
    <h1>Your Current Order</h1>
    <form id="orderStatusForm">
        <?php foreach ($statusLabels as $statusCode => $statusLabel): ?>
            <div>
                <input type="checkbox" id="<?php echo $statusLabel; ?>" name="order_status" value="<?php echo $statusLabel; ?>" <?php echo ($orderStatus == $statusCode) ? 'checked' : ''; ?> disabled>
                <label for="<?php echo $statusLabel; ?>"><b><strong><?php echo strtoupper($statusLabel); ?></strong></b></label>
            </div>
        <?php endforeach; ?>
    </form>
</section>

<footer>
    <p>© 2024 ReSSSTaurant. All rights reserved.</p>
</footer>

</body>
</html>
