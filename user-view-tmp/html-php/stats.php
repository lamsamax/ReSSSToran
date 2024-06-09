<?php
global $dbc;
include '../../authorization.php';

function fetchStats($dbc, $query, $returnArray = false) {
    $result = mysqli_query($dbc, $query);
    if (!$result) {
        die('Query failed: ' . mysqli_error($dbc));
    }
    if ($returnArray) {
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }
    return $result;
}

// Convert date from d/m/Y to Y-m-d
function convertToYmd($date) {
    $dateObject = DateTime::createFromFormat('d/m/Y', $date);
    return $dateObject ? $dateObject->format('Y-m-d') : null;
}

// Convert date from Y-m-d to d/m/Y
function convertToDmy($date) {
    $dateObject = DateTime::createFromFormat('Y-m-d', $date);
    return $dateObject ? $dateObject->format('d/m/Y') : null;
}

$startDate = isset($_GET['startDate']) ? convertToYmd($_GET['startDate']) : date('Y-m-d', strtotime('-7 days'));
$endDate = isset($_GET['endDate']) ? convertToYmd($_GET['endDate']) : date('Y-m-d');

$dateCondition = "DATE(o.orderDate) BETWEEN '$startDate' AND '$endDate'";

// Query to get daily sales data
$query = "SELECT DATE(o.orderDate) AS order_date, SUM(oi.quantity * i.price) AS daily_sales
          FROM ORDERS o
          JOIN ORDER_ITEM oi ON o.orderID = oi.orderID
          JOIN ITEM i ON oi.itemID = i.itemID
          WHERE $dateCondition
          GROUP BY DATE(o.orderDate)
          ORDER BY order_date";
$data = fetchStats($dbc, $query, true);

// Log data for debugging
echo '<script>console.log(' . json_encode($data) . ');</script>';
echo '<script>console.log("Query:", ' . json_encode($query) . ');</script>';

$displayStartDate = $_GET['startDate'] ?? convertToDmy(date('Y-m-d', strtotime('-7 days')));
$displayEndDate = $_GET['endDate'] ?? convertToDmy(date('Y-m-d'));
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Statistics Dashboard</title>
    <link rel="stylesheet" href="../CSS/stats.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
    <script>
        function updateFilter() {
            var startDate = document.getElementById('startDate').value;
            var endDate = document.getElementById('endDate').value;
            window.location.href = "?startDate=" + startDate + "&endDate=" + endDate;
        }

        function toggleVisibility(sectionId) {
            var content = document.getElementById(sectionId);
            if (content.style.display === "none" || content.style.display === "") {
                content.style.display = "block";
            } else {
                content.style.display = "none";
            }
        }
    </script>
</head>
<body>
<div class="container">
    <h1>Statistics Dashboard</h1>

    <div class="filter-section">
        <label for="startDate">Start Date:</label>
        <input type="text" id="startDate" name="startDate" value="<?php echo $displayStartDate; ?>" required pattern="\d{2}/\d{2}/\d{4}" placeholder="dd/mm/yyyy">

        <label for="endDate">End Date:</label>
        <input type="text" id="endDate" name="endDate" value="<?php echo $displayEndDate; ?>" required pattern="\d{2}/\d{2}/\d{4}" placeholder="dd/mm/yyyy">

        <button onclick="updateFilter()">Apply</button>
    </div>

    <div class="stats-section">
        <button onclick="toggleVisibility('dailySales')">Daily Sales</button>
        <div id="dailySales" style="display: none;">
            <canvas id="dailySalesChart" width="300px" height="300px"></canvas>
        </div>
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
                echo "<p>Category: " . htmlspecialchars($row['categoryName']) . " - Total Sales: BAM " . $row['total_sales'] . "</p>";
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
                echo "<p>Average Order Value: BAM " . $row['average_order_value'] . "</p>";
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
                echo "<p>Customer: " . htmlspecialchars($row['name']) . " - Total Spent: BAM " . $row['total_spent'] . "</p>";
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
                echo "<p>Month: " . htmlspecialchars($row['order_month']) . " - Monthly Sales:  BAM " . $row['monthly_sales'] . "</p>";
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
                echo "<p>Payment Method: " . htmlspecialchars($row['paymentMethod']) . " - Total Sales:  BAM " . $row['total_sales'] . "</p>";
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
                echo "<p>Delivery Type: " . htmlspecialchars($row['deliveryOption']) . " - Total Revenue: BAM " . $row['total_revenue'] . "</p>";
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

    <div class="stats-section">
        <button onclick="toggleVisibility('fiveTimesOrdered')">Items Ordered More Than Five Times</button>
        <div id="fiveTimesOrdered" style="display: none;">
            <?php
            $order_threshold = 5;

            $query = "SELECT i.name, COUNT(oi.itemID) AS order_count
                  FROM ORDER_ITEM oi
                  JOIN ITEM i ON oi.itemID = i.itemID
                  GROUP BY i.itemID
                  HAVING order_count > $order_threshold";

            $result = fetchStats($dbc, $query);
            echo "<p>Items ordered more than $order_threshold times:</p>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<p>Item: " . htmlspecialchars($row['name']) . " - Orders: " . $row['order_count'] . "</p>";
            }
            ?>
        </div>
    </div>

    <div class="stats-section">
        <button onclick="toggleVisibility('distinctPrices')">Item Price History</button>
        <div id="distinctPrices" style="display: none;">
            <form method="POST">
                <label for="item_name">Enter Item Name:</label>
                <input type="text" id="item_name" name="item_name" required>
                <button type="submit" name="fetch_prices">Fetch Prices</button>
            </form>

            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['fetch_prices'])) {
                $itemName = mysqli_real_escape_string($dbc, $_POST['item_name']);

                // Fetch the item ID based on the item name
                $itemQuery = "SELECT itemID FROM ITEM WHERE name = '$itemName'";
                $itemResult = fetchStats($dbc, $itemQuery);

                if (mysqli_num_rows($itemResult) > 0) {
                    $itemRow = mysqli_fetch_assoc($itemResult);
                    $itemID = $itemRow['itemID'];

                    // Fetch distinct prices for the item
                    $priceQuery = "SELECT DISTINCT oi.price
                                   FROM ORDER_ITEM oi
                                   WHERE oi.itemID = $itemID";

                    $priceResult = fetchStats($dbc, $priceQuery);

                    if (mysqli_num_rows($priceResult) > 0) {
                        echo "<p>Distinct prices for item: " . htmlspecialchars($itemName) . ":</p>";
                        while ($row = mysqli_fetch_assoc($priceResult)) {
                            echo "<p>Price: BAM " . htmlspecialchars($row['price']) . "</p>";
                        }
                    } else {
                        echo "<p>No distinct prices found for item: " . htmlspecialchars($itemName) . ".</p>";
                    }
                } else {
                    echo "<p>No item found with the name: " . htmlspecialchars($itemName) . ".</p>";
                }
            }
            ?>
        </div>
    </div>

    <div class="stats-section">
        <button onclick="toggleVisibility('similarReviews')">Item Reviews Containing Word "Good"</button>
        <div id="similarReviews" style="display: none;">
            <?php
            $searchString = 'good';
            $query = "SELECT r.description, i.name AS item_name
                  FROM ITEMREVIEW r
                  JOIN ITEM i ON r.itemID = i.itemID
                  WHERE LOWER(r.description) LIKE LOWER('%$searchString%')";

            $result = fetchStats($dbc, $query);
            echo "<p>Item reviews containing the string '$searchString':</p>";
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<p>Item: " . htmlspecialchars($row['item_name']) . " - Review: " . htmlspecialchars($row['description']) . "</p>";
                }
            } else {
                echo "<p>No item reviews found containing the string '$searchString'.</p>";
            }
            ?>
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('dailySalesChart').getContext('2d');
        const data = <?php echo json_encode($data); ?>;

        console.log('Chart data:', data);  // Log the data to the console
        if (!Array.isArray(data) || data.length === 0) {
            console.error('Data is not an array or is empty');
            return;
        }

        const labels = data.map(item => item.order_date);
        const sales = data.map(item => item.daily_sales);

        console.log('Labels:', labels);  // Log labels for debugging
        console.log('Sales:', sales);    // Log sales for debugging

        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Daily Sales',
                    data: sales,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    x: {
                        type: 'time',
                        time: {
                            unit: 'day'
                        }
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
</body>
</html>
<?php
mysqli_close($dbc);
?>
