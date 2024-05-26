<?php
include ('../../authorization.php');
checkUserRole('customer');
global $dbc;


if (!isset($_SESSION['current_order_id'])) {
    die("Error: No current order ID set in session.");
}

$orderID = $_SESSION['current_order_id'];

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Current Order Status</title>
    <link rel="stylesheet" type="text/css" href="../css/currentorderstyle.css">
    <script src=https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js></script>
    <script>
        function fetchOrderStatus() {
            $.ajax({
                url: 'fetchstatus.php',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#order-status').text(data.statusText);
                    if (data.statusText === 'Delivered') { // If the order is delivered
                        window.location.href = 'review.php';
                    }
                }
            });
        }

        $(document).ready(function() {
            setInterval(fetchOrderStatus, 5000);
        });

    </script>
</head>
<body>
<h1>Current Order Status</h1>
<p id="order-status">Loading...</p>
</body>
</html>