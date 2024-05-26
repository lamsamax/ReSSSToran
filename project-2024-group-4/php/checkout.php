<?php
include ('../../authorization.php');
checkUserRole('customer');

if (!isset($_SESSION['order']) || empty($_SESSION['order'])) {
    echo "No items in your cart.";
    exit;
}

function calculateTotal() {
    $total = 0;
    foreach ($_SESSION['order'] as $item) {
        $total += $item['price'];
    }
    return $total;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Checkout</title>
    <link rel="stylesheet" type="text/css" href="../css/checkoutstyle.css">
    <script>
        function toggleRoomSelection() {
            const deliveryOption = document.getElementById('delivery-option').value;
            const roomSelection = document.getElementById('room-selection');
            if (deliveryOption === 'delivery') {
                roomSelection.style.display = 'block';
            } else {
                roomSelection.style.display = 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            toggleRoomSelection(); // Initial call to set the correct state
            document.getElementById('delivery-option').addEventListener('change', toggleRoomSelection);
        });
    </script>
</head>
<body>
<nav>
    <ul>
        <li><a href="../../user-view-tmp/html-php/index.php">Home</a></li>
        <li><a href="menu.php">Menu</a></li>
        <li><a href="../../user-view-tmp/html-php/gallery.php">Gallery</a></li>
        <li><a href="../../user-view-tmp/html-php/contact.php">Contact</a></li>
        <li><a href="../../user-view-tmp/html-php/profile.php">Profile</a></li>
    </ul>
</nav>

<section class="checkout-section">
    <h1>Checkout</h1>
    <div class="order-details">
        <h2>YOUR ORDER</h2>
        <?php
        foreach ($_SESSION['order'] as $index => $item) {
            echo "<p>" . $item['name'] . " - " . $item['price'] . "KM";
            echo "<form method='POST' style='display:inline;'>";
            echo "<input type='hidden' name='item_index' value='" . $index . "'>";
            echo "<button type='submit' name='remove_item' class='delete-btn'>Delete</button>";
            echo "</form>";
            echo "</p>";
        }
        ?>
        <p class="total"><strong>TOTAL - <?php echo calculateTotal(); ?>KM</strong></p>
        <form method="POST" action="processorder.php">
            <div>
                <label for="delivery-option">Choose delivery or takeout:</label>
                <select id="delivery-option" name="delivery_option">
                    <option value="delivery">Delivery</option>
                    <option value="takeout">Takeout</option>
                </select>
            </div>
            <div id="room-selection">
                <label for="room-id">Select Room:</label>
                <select id="room-id" name="room_id">
                    <?php
                    global $dbc;
                    $result = mysqli_query($dbc, "SELECT roomID, roomNumber FROM ROOM");
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<option value="' . $row['roomID'] . '">' . $row['roomNumber'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div>
                <label for="payment-method">Payment Method:</label>
                <select id="payment-method" name="payment_method">
                    <option value="cash">Cash</option>
                    <option value="card">Card</option>
                </select>
            </div>
            <input type="hidden" name="total_price" value="<?php echo calculateTotal(); ?>">
            <button type="submit" name="place_order">Place Order</button>
        </form>
    </div>
</section>
<footer>
    <p>© 2024 ReSSSTaurant. All rights reserved.</p>
</footer>
</body>
</html>
