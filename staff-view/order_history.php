<?php
session_start();
include "../authorization.php";
include "statsfunctions.php"; // Include the functions file
checkUserRole('customer');
global $dbc;

// Assuming user ID is stored in session after login
$customerID = $_SESSION['user_id'];
$orderHistory = getUserOrderHistory($customerID);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="order_history.css">
    <title>Order History</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="background-gradient"></div>
<div class="content">
    <?php echo displayOrderHistory($orderHistory); ?>
</div>
</body>
</html>
