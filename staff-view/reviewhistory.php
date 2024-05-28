<?php
session_start();
include('../authorization.php');
checkUserRole('customer');
include('../mysqli_connect.php');

global $dbc;

if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

$user_id = $_SESSION['user_id'];

function getUserItemReviews($user_id) {
    global $dbc;
    $sql = "SELECT ir.reviewID, ir.description, ir.grade, ir.reviewDate, i.name as itemName
            FROM ITEMREVIEW ir
            JOIN ITEM i ON ir.itemID = i.itemID
            WHERE ir.customerID = ? AND ir.grade <> 0
            ORDER BY ir.reviewDate DESC";
    $stmt = $dbc->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $reviews = [];
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }
    $stmt->close();
    return $reviews;
}

function getUserOrderReviews($user_id) {
    global $dbc;
    $sql = "SELECT o.orderID, o.review as description, o.grade, dr.actualTime
            FROM ORDERS o
            JOIN DELIVERY_ROOM dr ON o.orderID = dr.orderID
            WHERE o.customer = ? AND o.grade <> 0
            ORDER BY dr.actualTime DESC";
    $stmt = $dbc->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $reviews = [];
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }
    $stmt->close();
    return $reviews;
}

$itemReviews = getUserItemReviews($user_id);
$orderReviews = getUserOrderReviews($user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Review History</title>
    <link rel="stylesheet" href="reviewhistory.css">
</head>
<body>

<div class="content">
    <h2>My Item Review History</h2>
    <table>
        <tr><th>Review ID</th><th>Item Name</th><th>Description</th><th>Grade</th><th>Review Date</th></tr>
        <?php foreach ($itemReviews as $review): ?>
            <tr>
                <td><?php echo htmlspecialchars($review['reviewID']); ?></td>
                <td><?php echo htmlspecialchars($review['itemName']); ?></td>
                <td><?php echo htmlspecialchars($review['description']); ?></td>
                <td><?php echo htmlspecialchars($review['grade']); ?></td>
                <td><?php echo htmlspecialchars($review['reviewDate']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h2>My Order Review History</h2>
    <table>
        <tr><th>Order ID</th><th>Description</th><th>Grade</th><th>Actual Time</th></tr>
        <?php foreach ($orderReviews as $review): ?>
            <tr>
                <td><?php echo htmlspecialchars($review['orderID']); ?></td>
                <td><?php echo htmlspecialchars($review['description']); ?></td>
                <td><?php echo htmlspecialchars($review['grade']); ?></td>
                <td><?php echo htmlspecialchars($review['actualTime']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>
