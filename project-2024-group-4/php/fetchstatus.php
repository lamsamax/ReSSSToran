<?php
include '../mysqli_connect.php';
session_start();
global $dbc;

if (!isset($_SESSION['current_order_id'])) {
    die("Error: No current order ID set in session.");
}

$orderID = $_SESSION['current_order_id'];
$sql = "SELECT status FROM ORDERS WHERE orderID = ?";
$stmt = $dbc->prepare($sql);

if (!$stmt) {
    die("Error preparing statement: " . $dbc->error);
}

$stmt->bind_param("i", $orderID);
$stmt->execute();
$stmt->bind_result($status);
$stmt->fetch();
$stmt->close();

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

$statusText = getStatusText($status);
echo json_encode(['statusText' => $statusText]);
