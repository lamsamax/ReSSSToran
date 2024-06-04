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

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'last_week';

switch ($filter) {
    case 'today':
        $dateCondition = "DATE(o.orderDate) = CURDATE()";
        break;
    case 'yesterday':
        $dateCondition = "DATE(o.orderDate) = CURDATE() - INTERVAL 1 DAY";
        break;
    case 'last_week':
        $dateCondition = "DATE(o.orderDate) >= CURDATE() - INTERVAL 7 DAY";
        break;
    case 'last_month':
        $dateCondition = "DATE(o.orderDate) >= CURDATE() - INTERVAL 1 MONTH";
        break;
    case 'last_year':
        $dateCondition = "DATE(o.orderDate) >= CURDATE() - INTERVAL 1 YEAR";
        break;
    default:
        $dateCondition = "DATE(o.orderDate) >= CURDATE() - INTERVAL 5 DAY"; // Default to last 5 days
        break;
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
    <script>
        function toggleVisibility(sectionId) {
            var content = document.getElementById(sectionId);
            if (content.style.display === "none" || content.style.display === "") {
                content.style.display = "block";
            } else {
                content.style.display = "none";
            }
        }

        function updateFilter() {
            var filter = document.getElementById('filter').value;
            window.location.href = "?filter=" + filter;
        }
    </script>
</head>
<body>
<div class="container">
    <h1>Statistics Dashboard</h1>

    <div class="filter-section">
        <label for="filter">Select Time Range:</label>
        <select id="filter" onchange="updateFilter()">
            <option value="today" <?php if ($filter == 'today') echo 'selected'; ?>>Today</option>
            <option value="yesterday" <?php if ($filter == 'yesterday') echo 'selected'; ?>>Yesterday</option>
            <option value="last_week" <?php if ($filter == 'last_week') echo 'selected'; ?>>Last Week</option>
            <option value="last_month" <?php if ($filter == 'last_month') echo 'selected'; ?>>Last Month</option>
            <option value="last_year" <?php if ($filter == 'last_year') echo 'selected'; ?>>Last Year</option>
        </select>
    </div>

    <div class="stats-section">
        <button onclick="toggleVisibility('mostBoughtItem')">Most Bought Item</button>
        <div id="mostBoughtItem" style="display: none;">
            <?php
            $query = "SELECT i.name, COUNT(oi.itemID) AS item_count
                      FROM ORDER_ITEM oi
                      JOIN ITEM i ON oi.itemID = i.itemID
                      JOIN ORDERS o ON oi.orderID = o.orderID
                      WHERE $dateCondition
                      GROUP BY oi.itemID, i.name
                      ORDER BY item_count DESC
                      LIMIT 1";
            $result = fetchStats($dbc, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<p>Item: " . htmlspecialchars($row['name']) . " - Bought: " . $row['item_count'] . " times</p>";
            }
            ?>
        </div>
    </div>

    <div class="stats-section">
        <button onclick="toggleVisibility('topCustomerByOrders')">Top Customer by Orders</button>
        <div id="topCustomerByOrders" style="display: none;">
            <?php
            $query = "SELECT u.name, COUNT(o.orderID) AS order_count
                      FROM ORDERS o
                      JOIN CUSTOMER u ON o.customer = u.customerID
                      WHERE $dateCondition
                      GROUP BY o.customer, u.name
                      ORDER BY order_count DESC
                      LIMIT 1";
            $result = fetchStats($dbc, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<p>Customer: " . htmlspecialchars($row['name']) . " - Orders: " . $row['order_count'] . "</p>";
            }
            ?>
        </div>
    </div>

    <div class="stats-section">
        <button onclick="toggleVisibility('totalSalesPerCategory')">Total Sales Per Category</button>
        <div id="totalSalesPerCategory" style="display: none;">
            <?php
            $query = "SELECT c.name AS categoryName, SUM(oi.quantity * i.price) AS total_sales
                      FROM ORDER_ITEM oi
                      JOIN ITEM i ON oi.itemID = i.itemID
                      JOIN CATEGORY c ON i.categoryID = c.categoryID
                      JOIN ORDERS o ON oi.orderID = o.orderID
                      WHERE $dateCondition
                      GROUP BY c.categoryID, c.name
                      ORDER BY total_sales DESC";
            $result = fetchStats($dbc, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<p>Category: " . htmlspecialchars($row['categoryName']) . " - Total Sales: $" . $row['total_sales'] . "</p>";
            }
            ?>
        </div>
    </div>

    <div class="stats-section">
        <button onclick="toggleVisibility('dailySales')">Daily Sales</button>
        <div id="dailySales" style="display: none;">
            <?php
            $query = "SELECT DATE(o.orderDate) AS order_date, SUM(oi.quantity * i.price) AS daily_sales
                      FROM ORDERS o
                      JOIN ORDER_ITEM oi ON o.orderID = oi.orderID
                      JOIN ITEM i ON oi.itemID = i.itemID
                      WHERE $dateCondition
                      GROUP BY DATE(o.orderDate)
                      ORDER BY order_date";
            $result = fetchStats($dbc, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<p>Date: " . htmlspecialchars($row['order_date']) . " - Daily Sales: $" . $row['daily_sales'] . "</p>";
            }
            ?>
        </div>
    </div>

    <div class="stats-section">
        <button onclick="toggleVisibility('averageOrderValue')">Average Order Value</button>
        <div id="averageOrderValue" style="display: none;">
            <?php
            $query = "SELECT AVG(order_total) AS average_order_value
                      FROM (
                          SELECT o.orderID, SUM(oi.quantity * i.price) AS order_total
                          FROM ORDERS o
                          JOIN ORDER_ITEM oi ON o.orderID = oi.orderID
                          JOIN ITEM i ON oi.itemID = i.itemID
                          WHERE $dateCondition
                          GROUP BY o.orderID
                      ) AS order_totals";
            $result = fetchStats($dbc, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<p>Average Order Value: $" . $row['average_order_value'] . "</p>";
            }
            ?>
        </div>
    </div>

    <div class="stats-section">
        <button onclick="toggleVisibility('mostPopularCategory')">Most Popular Category</button>
        <div id="mostPopularCategory" style="display: none;">
            <?php
            $query = "SELECT c.name AS categoryName, COUNT(oi.itemID) AS category_count
                      FROM ORDER_ITEM oi
                      JOIN ITEM i ON oi.itemID = i.itemID
                      JOIN CATEGORY c ON i.categoryID = c.categoryID
                      JOIN ORDERS o ON oi.orderID = o.orderID
                      WHERE $dateCondition
                      GROUP BY c.categoryID, c.name
                      ORDER BY category_count DESC
                      LIMIT 1";
            $result = fetchStats($dbc, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<p>Category: " . htmlspecialchars($row['categoryName']) . " - Ordered: " . $row['category_count'] . " times</p>";
            }
            ?>
        </div>
    </div>

    <div class="stats-section">
        <button onclick="toggleVisibility('topCustomersBySpending')">Top Customers by Spending</button>
        <div id="topCustomersBySpending" style="display: none;">
            <?php
            $query = "SELECT u.name, SUM(oi.quantity * i.price) AS total_spent
                      FROM ORDERS o
                      JOIN CUSTOMER u ON o.customer = u.customerID
                      JOIN ORDER_ITEM oi ON o.orderID = oi.orderID
                      JOIN ITEM i ON oi.itemID = i.itemID
                      WHERE $dateCondition
                      GROUP BY o.customer, u.name
                      ORDER BY total_spent DESC
                      LIMIT 10";
            $result = fetchStats($dbc, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<p>Customer: " . htmlspecialchars($row['name']) . " - Total Spent: $" . $row['total_spent'] . "</p>";
            }
            ?>
        </div>
    </div>

    <div class="stats-section">
        <button onclick="toggleVisibility('monthlySales')">Monthly Sales</button>
        <div id="monthlySales" style="display: none;">
            <?php
            $query = "SELECT DATE_FORMAT(o.orderDate, '%Y-%m') AS order_month, SUM(oi.quantity * i.price) AS monthly_sales
                      FROM ORDERS o
                      JOIN ORDER_ITEM oi ON o.orderID = oi.orderID
                      JOIN ITEM i ON oi.itemID = i.itemID
                      WHERE $dateCondition
                      GROUP BY order_month
                      ORDER BY order_month";
            $result = fetchStats($dbc, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<p>Month: " . htmlspecialchars($row['order_month']) . " - Monthly Sales: $" . $row['monthly_sales'] . "</p>";
            }
            ?>
        </div>
    </div>

    <div class="stats-section">
        <button onclick="toggleVisibility('ordersByDayOfWeek')">Orders by Day of the Week</button>
        <div id="ordersByDayOfWeek" style="display: none;">
            <?php
            $query = "SELECT DAYNAME(o.orderDate) AS day_of_week, COUNT(o.orderID) AS order_count
                      FROM ORDERS o
                      WHERE $dateCondition
                      GROUP BY day_of_week
                      ORDER BY FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')";
            $result = fetchStats($dbc, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<p>Day: " . htmlspecialchars($row['day_of_week']) . " - Orders: " . $row['order_count'] . "</p>";
            }
            ?>
        </div>
    </div>

    <div class="stats-section">
        <button onclick="toggleVisibility('totalSalesByPaymentMethod')">Total Sales by Payment Method</button>
        <div id="totalSalesByPaymentMethod" style="display: none;">
            <?php
            $query = "SELECT o.paymentMethod, SUM(oi.quantity * i.price) AS total_sales
                      FROM ORDERS o
                      JOIN ORDER_ITEM oi ON o.orderID = oi.orderID
                      JOIN ITEM i ON oi.itemID = i.itemID
                      WHERE $dateCondition
                      GROUP BY o.paymentMethod
                      ORDER BY total_sales DESC";
            $result = fetchStats($dbc, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<p>Payment Method: " . htmlspecialchars($row['paymentMethod']) . " - Total Sales: $" . $row['total_sales'] . "</p>";
            }
            ?>
        </div>
    </div>

    <div class="stats-section">
        <button onclick="toggleVisibility('customerOrderFrequency')">Customer Order Frequency</button>
        <div id="customerOrderFrequency" style="display: none;">
            <?php
            $query = "SELECT u.name, COUNT(o.orderID) AS order_count
                      FROM ORDERS o
                      JOIN CUSTOMER u ON o.customer = u.customerID
                      WHERE $dateCondition
                      GROUP BY o.customer, u.name
                      ORDER BY order_count DESC";
            $result = fetchStats($dbc, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<p>Customer: " . htmlspecialchars($row['name']) . " - Orders: " . $row['order_count'] . "</p>";
            }
            ?>
        </div>
    </div>

    <div class="stats-section">
        <button onclick="toggleVisibility('revenueByDeliveryType')">Revenue by Delivery Type</button>
        <div id="revenueByDeliveryType" style="display: none;">
            <?php
            $query = "SELECT o.deliveryOption, SUM(oi.quantity * i.price) AS total_revenue
                      FROM ORDERS o
                      JOIN ORDER_ITEM oi ON o.orderID = oi.orderID
                      JOIN ITEM i ON oi.itemID = i.itemID
                      WHERE $dateCondition
                      GROUP BY o.deliveryOption
                      ORDER BY total_revenue DESC";
            $result = fetchStats($dbc, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<p>Delivery Type: " . htmlspecialchars($row['deliveryOption']) . " - Total Revenue: $" . $row['total_revenue'] . "</p>";
            }
            ?>
        </div>
    </div>

    <div class="stats-section">
        <button onclick="toggleVisibility('peakOrderTimes')">Peak Order Times</button>
        <div id="peakOrderTimes" style="display: none;">
            <?php
            $query = "SELECT HOUR(o.orderDate) AS order_hour, COUNT(o.orderID) AS order_count
                      FROM ORDERS o
                      WHERE $dateCondition
                      GROUP BY order_hour
                      ORDER BY order_hour";
            $result = fetchStats($dbc, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<p>Hour: " . htmlspecialchars($row['order_hour']) . " - Orders: " . $row['order_count'] . "</p>";
            }
            ?>
        </div>
    </div>
</div>
</body>
</html>
<?php
mysqli_close($dbc);
?>
