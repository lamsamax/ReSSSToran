<?php
include "../authorization.php";
include "statsfunctions.php"; // Include the functions file
checkUserRole('staff');
global $dbc;

// Start the session if it hasn't been started yet
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the staff member is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You need to log in to view your orders.";
    exit();
}

$staffId = $_SESSION['user_id'];

// Check connection
if ($dbc->connect_error) {
    die("Connection failed: " . $dbc->connect_error);
}

// Query to get orders taken by the logged-in staff member
$sqlTaken = "
SELECT * FROM 
    StaffOrdersDetailed
WHERE 
    takestaffID = ? AND grade <> 0
ORDER BY grade DESC 
";

$stmtTaken = $dbc->prepare($sqlTaken);
$stmtTaken->bind_param("i", $staffId);
$stmtTaken->execute();
$resultTaken = $stmtTaken->get_result();

// Query to get orders delivered by the logged-in staff member
$sqlDelivered = "
SELECT * FROM 
    StaffOrdersDetailed
WHERE 
    deliverStaffId = ? AND grade <> 0
ORDER BY grade DESC 
";

$stmtDelivered = $dbc->prepare($sqlDelivered);
$stmtDelivered->bind_param("i", $staffId);
$stmtDelivered->execute();
$resultDelivered = $stmtDelivered->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Staff Orders</title>
    <link rel="stylesheet" href="staff_orders.css">
    <script>
        function toggleDetails(prefix, orderId) {
            var detailsRow = document.getElementById(prefix + '-details-' + orderId);
            if (detailsRow.style.display === 'none') {
                detailsRow.style.display = 'table-row';
            } else {
                detailsRow.style.display = 'none';
            }
        }

        function showTable(tableId) {
            var tables = document.querySelectorAll('.orders-table');
            tables.forEach(function(table) {
                table.style.display = 'none';
            });
            document.getElementById(tableId).style.display = 'block';
        }

        window.onload = function() {
            showTable('takenTable');
        }
    </script>
</head>
<body>
<div class="container">
    <div class="buttons">
        <button onclick="showTable('takenTable')">Orders Taken</button>
        <button onclick="showTable('deliveredTable')">Orders Delivered</button>
    </div>

    <div id="takenTable" class="orders-table">
        <h2>Orders Taken by You</h2>
        <?php
        if ($resultTaken->num_rows > 0) {
            echo "<table>";
            echo "<tr>
                    <th>Order ID</th>
                    <th>Order Date</th>
                    <th>Grade</th>
                    <th>Customer Name</th>
                    <th>Customer Surname</th>
                    <th>Total Price</th>
                  </tr>";

            // Output data of each row
            while ($row = $resultTaken->fetch_assoc()) {
                echo "<tr onclick=\"toggleDetails('taken', " . $row["orderId"] . ")\">
                        <td>" . htmlspecialchars($row["orderId"]) . "</td>
                        <td>" . htmlspecialchars($row["orderDate"]) . "</td>
                        <td>" . htmlspecialchars($row["grade"]) . "</td>
                        <td>" . htmlspecialchars($row["customerName"]) . "</td>
                        <td>" . htmlspecialchars($row["customerSurname"]) . "</td>
                        <td>" . htmlspecialchars($row["total_price"]) . "</td>
                      </tr>";
                echo "<tr id='taken-details-" . $row["orderId"] . "' class='details'>
                        <td colspan='8'><strong>Items:</strong> " . htmlspecialchars($row["items"]) . "</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No orders found that you have taken.</p>";
        }

        $stmtTaken->close();
        ?>
    </div>

    <div id="deliveredTable" class="orders-table">
        <h2>Orders Delivered by You</h2>
        <?php
        if ($resultDelivered->num_rows > 0) {
            echo "<table>";
            echo "<tr>
                    <th>Order ID</th>
                    <th>Order Date</th>
                    <th>Grade</th>
                    <th>Customer Name</th>
                    <th>Customer Surname</th>
                    <th>Total Price</th>
                  </tr>";

            // Output data of each row
            while ($row = $resultDelivered->fetch_assoc()) {
                echo "<tr onclick=\"toggleDetails('delivered', " . $row["orderId"] . ")\">
                        <td>" . htmlspecialchars($row["orderId"]) . "</td>
                        <td>" . htmlspecialchars($row["orderDate"]) . "</td>
                        <td>" . htmlspecialchars($row["grade"]) . "</td>
                        <td>" . htmlspecialchars($row["customerName"]) . "</td>
                        <td>" . htmlspecialchars($row["customerSurname"]) . "</td>
                        <td>" . htmlspecialchars($row["total_price"]) . "</td>
                      </tr>";
                echo "<tr id='delivered-details-" . $row["orderId"] . "' class='details'>
                        <td colspan='8'><strong>Items:</strong> " . htmlspecialchars($row["items"]) . "</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No orders found that you have delivered.</p>";
        }

        $stmtDelivered->close();
        $dbc->close();
        ?>
    </div>
</div>
</body>
</html>
