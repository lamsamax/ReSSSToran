<?php
include('../authorization.php');
checkUserRole('admin');

global $dbc;

function getAllItemReviews() {
    global $dbc;
    $sql = "SELECT ir.reviewID, ir.description, ir.grade, ir.reviewDate, i.name as itemName, c.name as customerName, c.surname as customerSurname
            FROM ITEMREVIEW ir
            JOIN ITEM i ON ir.itemID = i.itemID
            JOIN CUSTOMER c ON ir.customerID = c.customerID
            WHERE ir.grade <> 0
            ORDER BY ir.reviewDate DESC";
    $result = $dbc->query($sql);
    $reviews = [];
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }
    return $reviews;
}

function getAllOrderReviews() {
    global $dbc;
    $sql = "SELECT o.orderID, o.review as description, o.grade, o.orderDate as reviewDate, c.name as customerName, c.surname as customerSurname
            FROM ORDERS o
            JOIN CUSTOMER c ON o.customer = c.customerID
            WHERE o.grade <> 0
            ORDER BY o.orderdate DESC";
    $result = $dbc->query($sql);
    $reviews = [];
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }
    return $reviews;
}

$itemReviews = getAllItemReviews();
$orderReviews = getAllOrderReviews();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Review Management</title>
    <link rel="stylesheet" href="admin_review_history.css">
</head>
<body>
<div class="container">
    <h1>Admin Review Management</h1>

    <h2>All Item Review History</h2>
    <table>
        <tr><th>Review ID</th><th>Customer Name</th><th>Item Name</th><th>Description</th><th>Grade</th><th>Review Date</th></tr>
        <?php foreach ($itemReviews as $review): ?>
            <tr>
                <td><?php echo htmlspecialchars($review['reviewID']); ?></td>
                <td><?php echo htmlspecialchars($review['customerName']) . ' ' . htmlspecialchars($review['customerSurname']); ?></td>
                <td><?php echo htmlspecialchars($review['itemName']); ?></td>
                <td><?php echo htmlspecialchars($review['description']); ?></td>
                <td><?php echo htmlspecialchars($review['grade']); ?></td>
                <td><?php echo htmlspecialchars($review['reviewDate']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h2>All Order Review History</h2>
    <table>
        <tr><th>Order ID</th><th>Customer Name</th><th>Description</th><th>Grade</th><th>Review Date</th></tr>
        <?php foreach ($orderReviews as $review): ?>
            <tr>
                <td><?php echo htmlspecialchars($review['orderID']); ?></td>
                <td><?php echo htmlspecialchars($review['customerName']) . ' ' . htmlspecialchars($review['customerSurname']); ?></td>
                <td><?php echo htmlspecialchars($review['description']); ?></td>
                <td><?php echo htmlspecialchars($review['grade']); ?></td>
                <td><?php echo htmlspecialchars($review['reviewDate']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>
