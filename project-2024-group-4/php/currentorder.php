<?php
session_start();
include '../mysqli_connect.php';

global $dbc;
$user_id = $_SESSION['user_id'];
$orderQuery = "SELECT * FROM ORDERS WHERE customer='$user_id' ORDER BY orderID DESC LIMIT 1"; // gets the latest user order, may need modifications
$orderResult = mysqli_query($dbc, $orderQuery);
$order = mysqli_fetch_assoc($orderResult);
$orderStatus = $order['status'];

$statusLabels = [
    0 => 'sent',
    1 => 'accepted',
    2 => 'inMaking',
    3 => 'done',
    4 => 'inDelivery',
    5 => 'delivered'
];

mysqli_close($dbc);
?>

<!doctype html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
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
        <div>
            <input type="checkbox" id="accepted" name="order_status" value="accepted" <?php echo ($orderStatus == 1) ? 'checked' : ''; ?> disabled>
            <label for="accepted"><b><strong>ACCEPTED</strong></b></label>
        </div>
        <div>
            <input type="checkbox" id="inMaking" name="order_status" value="inMaking" <?php echo ($orderStatus == 2) ? 'checked' : ''; ?> disabled>
            <label for="inMaking"><b><strong>IN MAKING</strong></b></label>
        </div>
        <div>
            <input type="checkbox" id="done" name="order_status" value="done" <?php echo ($orderStatus == 3) ? 'checked' : ''; ?> disabled>
            <label for="done"><b><strong>DONE</strong></b></label>
        </div>
        <div>
            <input type="checkbox" id="inDelivery" name="order_status" value="inDelivery" <?php echo ($orderStatus == 4) ? 'checked' : ''; ?> disabled>
            <label for="inDelivery"><b><strong>IN DELIVERY</strong></b></label>
        </div>
        <div>
            <input type="checkbox" id="delivered" name="order_status" value="delivered" <?php echo ($orderStatus == 5) ? 'checked' : ''; ?> disabled>
            <label for="delivered"><b><strong>DELIVERED</strong></b></label>
        </div>
    </form>
</section>

<footer>
    <p>© 2024 ReSSSTaurant. All rights reserved.</p>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('delivery-type').textContent = localStorage.getItem('deliveryOption');
        document.getElementById('payment-method-display').textContent = localStorage.getItem('paymentMethod');
    });
</script>

</body>
</html>


