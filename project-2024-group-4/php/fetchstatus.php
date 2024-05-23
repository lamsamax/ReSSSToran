<?php
session_start();

include '../mysqli_connect.php';



global $dbc;

$user_id = $_SESSION['user_id'];

$orderQuery = "SELECT status FROM ORDERS WHERE customer='$user_id' ORDER BY orderID DESC LIMIT 1";

$orderResult = mysqli_query($dbc, $orderQuery);

$order = mysqli_fetch_assoc($orderResult);

$orderStatus = $order['status'];



echo json_encode(['status' => $orderStatus]);