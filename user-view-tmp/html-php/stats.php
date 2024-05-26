<?php
global $dbc;
include '../../authorization.php';

function fetchStats($dbc, $query) {
    $result = mysqli_query($dbc, $query);
    if (!$result) {
        die('Query failed: ' . mysqli_error($dbc));
    }
    return $result;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Statistics Dashboard</title>
    <link rel="stylesheet" href="../CSS/stats.css">
</head>
<body>
<div class="container">
    <h1>Statistics Dashboard</h1>
    <div class="stats-section">
        <h2>Most Bought Item</h2>
        <?php
        $query = "SELECT i.name, COUNT(oi.itemID) AS item_count
                  FROM ORDER_ITEM oi
                  JOIN ITEM i ON oi.itemID = i.itemID
                  GROUP BY oi.itemID, i.name
                  ORDER BY item_count DESC
                  LIMIT 1";
        $result = fetchStats($dbc, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<p>Item: " . htmlspecialchars($row['name']) . " - Bought: " . $row['item_count'] . " times</p>";
        }
        ?>
    </div>
    <div class="stats-section">
        <h2>Top Customer by Orders</h2>
        <?php
        $query = "SELECT u.name, COUNT(o.orderID) AS order_count
                  FROM ORDERS o
                  JOIN CUSTOMER u ON o.customer = u.customerID
                  GROUP BY o.customer, u.name
                  ORDER BY order_count DESC
                  LIMIT 1";
        $result = fetchStats($dbc, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<p>Customer: " . htmlspecialchars($row['name']) . " - Orders: " . $row['order_count'] . "</p>";
        }
        ?>
    </div>
    <div class="stats-section">
        <h2>Total Sales Per Category</h2>
        <?php
        $query = "SELECT c.name AS categoryName, SUM(oi.quantity * i.price) AS total_sales
                  FROM ORDER_ITEM oi
                  JOIN ITEM i ON oi.itemID = i.itemID
                  JOIN CATEGORY c ON i.categoryID = c.categoryID
                  GROUP BY c.categoryID, c.name
                  ORDER BY total_sales DESC";
        $result = fetchStats($dbc, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<p>Category: " . htmlspecialchars($row['categoryName']) . " - Total Sales: $" . $row['total_sales'] . "</p>";
        }
        ?>
    </div>
    <div class="stats-section">
        <h2>Daily Sales</h2>
        <?php
        $query = "SELECT DATE(o.orderDate) AS order_date, SUM(oi.quantity * i.price) AS daily_sales
                  FROM ORDERS o
                  JOIN ORDER_ITEM oi ON o.orderID = oi.orderID
                  JOIN ITEM i ON oi.itemID = i.itemID
                  GROUP BY DATE(o.orderDate)
                  ORDER BY order_date";
        $result = fetchStats($dbc, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<p>Date: " . htmlspecialchars($row['order_date']) . " - Daily Sales: $" . $row['daily_sales'] . "</p>";
        }
        ?>
    </div>
    <div class="stats-section">
        <h2>Average Order Value</h2>
        <?php
        $query = "SELECT AVG(order_total) AS average_order_value
                  FROM (
                      SELECT o.orderID, SUM(oi.quantity * i.price) AS order_total
                      FROM ORDERS o
                      JOIN ORDER_ITEM oi ON o.orderID = oi.orderID
                      JOIN ITEM i ON oi.itemID = i.itemID
                      GROUP BY o.orderID
                  ) AS order_totals";
        $result = fetchStats($dbc, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<p>Average Order Value: $" . $row['average_order_value'] . "</p>";
        }
        ?>
    </div>
    <div class="stats-section">
        <h2>Most Popular Category</h2>
        <?php
        $query = "SELECT c.name AS categoryName, COUNT(oi.itemID) AS category_count
                  FROM ORDER_ITEM oi
                  JOIN ITEM i ON oi.itemID = i.itemID
                  JOIN CATEGORY c ON i.categoryID = c.categoryID
                  GROUP BY c.categoryID, c.name
                  ORDER BY category_count DESC
                  LIMIT 1";
        $result = fetchStats($dbc, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<p>Category: " . htmlspecialchars($row['categoryName']) . " - Ordered: " . $row['category_count'] . " times</p>";
        }
        ?>
    </div>

    <div class="stats-section">
        <h2>Top Customers by Spending</h2>
        <?php
        $query = "SELECT u.name, SUM(oi.quantity * i.price) AS total_spent
                  FROM ORDERS o
                  JOIN CUSTOMER u ON o.customer = u.customerID
                  JOIN ORDER_ITEM oi ON o.orderID = oi.orderID
                  JOIN ITEM i ON oi.itemID = i.itemID
                  GROUP BY o.customer, u.name
                  ORDER BY total_spent DESC
                  LIMIT 10";
        $result = fetchStats($dbc, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<p>Customer: " . htmlspecialchars($row['name']) . " - Total Spent: $" . $row['total_spent'] . "</p>";
        }
        ?>
    </div>

    <div class="stats-section">
        <h2>Monthly Sales</h2>
        <?php
        $query = "SELECT DATE_FORMAT(o.orderDate, '%Y-%m') AS order_month, SUM(oi.quantity * i.price) AS monthly_sales
                  FROM ORDERS o
                  JOIN ORDER_ITEM oi ON o.orderID = oi.orderID
                  JOIN ITEM i ON oi.itemID = i.itemID
                  GROUP BY order_month
                  ORDER BY order_month";
        $result = fetchStats($dbc, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<p>Month: " . htmlspecialchars($row['order_month']) . " - Monthly Sales: $" . $row['monthly_sales'] . "</p>";
        }
        ?>
    </div>

    <div class="stats-section">
        <h2>Orders by Day of the Week</h2>
        <?php
        $query = "SELECT DAYNAME(o.orderDate) AS day_of_week, COUNT(o.orderID) AS order_count
                  FROM ORDERS o
                  GROUP BY day_of_week
                  ORDER BY FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')";
        $result = fetchStats($dbc, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<p>Day: " . htmlspecialchars($row['day_of_week']) . " - Orders: " . $row['order_count'] . "</p>";
        }
        ?>
    </div>

    <div class="stats-section">
        <h2>Total Sales by Payment Method</h2>
        <?php
        $query = "SELECT o.paymentMethod, SUM(oi.quantity * i.price) AS total_sales
                  FROM ORDERS o
                  JOIN ORDER_ITEM oi ON o.orderID = oi.orderID
                  JOIN ITEM i ON oi.itemID = i.itemID
                  GROUP BY o.paymentMethod
                  ORDER BY total_sales DESC";
        $result = fetchStats($dbc, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<p>Payment Method: " . htmlspecialchars($row['paymentMethod']) . " - Total Sales: $" . $row['total_sales'] . "</p>";
        }
        ?>
    </div>

    <div class="stats-section">
        <h2>Customer Order Frequency</h2>
        <?php
        $query = "SELECT u.name, COUNT(o.orderID) AS order_count
                  FROM ORDERS o
                  JOIN CUSTOMER u ON o.customer = u.customerID
                  GROUP BY o.customer, u.name
                  ORDER BY order_count DESC";
        $result = fetchStats($dbc, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<p>Customer: " . htmlspecialchars($row['name']) . " - Orders: " . $row['order_count'] . "</p>";
        }
        ?>
    </div>

    <div class="stats-section">
        <h2>Revenue by Delivery Type</h2>
        <?php
        $query = "SELECT o.deliveryOption, SUM(oi.quantity * i.price) AS total_revenue
                  FROM ORDERS o
                  JOIN ORDER_ITEM oi ON o.orderID = oi.orderID
                  JOIN ITEM i ON oi.itemID = i.itemID
                  GROUP BY o.deliveryOption
                  ORDER BY total_revenue DESC";
        $result = fetchStats($dbc, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<p>Delivery Type: " . htmlspecialchars($row['deliveryOption']) . " - Total Revenue: $" . $row['total_revenue'] . "</p>";
        }
        ?>
    </div>

    <div class="stats-section">
        <h2>Peak Order Times</h2>
        <?php
        $query = "SELECT HOUR(o.orderDate) AS order_hour, COUNT(o.orderID) AS order_count
                  FROM ORDERS o
                  GROUP BY order_hour
                  ORDER BY order_hour";
        $result = fetchStats($dbc, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<p>Hour: " . htmlspecialchars($row['order_hour']) . " - Orders: " . $row['order_count'] . "</p>";
        }
        ?>
    </div>
</div>
</body>
</html>
<?php
mysqli_close($dbc);
?>
