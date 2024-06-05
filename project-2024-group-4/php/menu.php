<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../../authorization.php');
checkUserRole('customer');

if (!isset($_SESSION['order'])) {
    $_SESSION['order'] = [];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $itemID = $_POST['item_id'] ?? null;
    $itemName = $_POST['item_name'] ?? null;
    $itemPrice = $_POST['item_price'] ?? null;
    $action = $_POST['action'] ?? null;

    if ($action === 'add') {
        addItemToOrder($itemID, $itemName, $itemPrice);
    } elseif ($action === 'increase') {
        increaseItemQuantity($itemID);
    } elseif ($action === 'decrease') {
        decreaseItemQuantity($itemID);
    }
}

function addItemToOrder($itemID, $itemName, $itemPrice) {
    foreach ($_SESSION['order'] as &$item) {
        if ($item['itemID'] == $itemID) {
            $item['quantity']++;
            return;
        }
    }
    $_SESSION['order'][] = [
        'itemID' => $itemID,
        'name' => $itemName,
        'price' => $itemPrice,
        'quantity' => 1,
    ];
}

function increaseItemQuantity($itemID) {
    foreach ($_SESSION['order'] as &$item) {
        if ($item['itemID'] == $itemID) {
            $item['quantity']++;
            return;
        }
    }
}

function decreaseItemQuantity($itemID) {
    foreach ($_SESSION['order'] as $key => &$item) {
        if ($item['itemID'] == $itemID) {
            $item['quantity']--;
            if ($item['quantity'] <= 0) {
                unset($_SESSION['order'][$key]);
                $_SESSION['order'] = array_values($_SESSION['order']);
            }
            return;
        }
    }
}

function calculateTotal() {
    $total = 0;
    foreach ($_SESSION['order'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="../css/menustyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Menu</title>
</head>
<body>
<button onclick="window.location.href='../../user-view-tmp/html-php/index.php'" class="home-button">
    <i class="fas fa-home"></i>
</button>

<nav>
    <div class="nav-container">
        <span class="categories-title" onclick="toggleMenu()">Categories</span>
        <ul>
            <?php
            global $dbc;
            $result = mysqli_query($dbc, "SELECT * FROM CATEGORY");
            if (!$result) {
                die('Query failed: ' . mysqli_error($dbc));
            }
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<li><a href='#" . strtolower(str_replace(' ', '-', $row['name'])) . "'>" . $row['name'] . "</a></li>";
            }
            ?>
        </ul>
    </div>
</nav>

<div class="items">
    <?php
    $categories_result = mysqli_query($dbc, "SELECT * FROM CATEGORY");
    if (!$categories_result) {
        die('Query failed: ' . mysqli_error($dbc));
    }
    while ($category = mysqli_fetch_assoc($categories_result)) {
        echo "<section id='" . strtolower(str_replace(' ', '-', $category['name'])) . "'>";
        echo "<h1>" . $category['name'] . "</h1>";

        if (!isset($category['categoryID'])) {
            die("Error: 'categoryID' key not found in category array.");
        }

        $items_result = mysqli_query($dbc, "SELECT itemID, name, description, price, imageUrl, avgGrade FROM ITEM WHERE categoryID=" . $category['categoryID']);
        if (!$items_result) {
            die('Query failed: ' . mysqli_error($dbc));
        }

        while ($item = mysqli_fetch_assoc($items_result)) {
            echo "<div class='item'>";
            echo "<img src='../" . $item['imageUrl'] . "' alt='" . $item['name'] . "' class='item-image'>";
            echo "<div class='item-details'>";
            echo "<p>" . $item['name'] . " - " . $item['price'] . "KM</p>";
            echo "<p class='item-description'>" . $item['description'] . "</p>";
            if ($item['avgGrade'] >= 3.0) {
                echo "<p class='item-grade'>Average Grade: " . $item['avgGrade'] . "</p>";
            }
            echo "<div class='item-buttons'>"; // Container for buttons
            echo "<form method='POST'>";
            echo "<input type='hidden' name='item_id' value='" . $item['itemID'] . "'>";
            echo "<input type='hidden' name='item_name' value='" . $item['name'] . "'>";
            echo "<input type='hidden' name='item_price' value='" . $item['price'] . "'>";
            echo "<input type='hidden' name='action' value='add'>";
            echo "<button type='submit' name='add_to_bag'>Add to bag</button>";
            echo "</form>";
            echo "<form method='GET' action='itemreview.php'>";
            echo "<input type='hidden' name='item_id' value='" . $item['itemID'] . "'>";
            echo "<button type='submit'>Review</button>";
            echo "</form>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }

        echo "</section>";
    }
    ?>
</div>

<div class="order-summary">
    <h1 class="checkoutheader">YOUR ORDER</h1>
    <?php
    foreach ($_SESSION['order'] as $index => $item) {
        echo "<p>" . $item['name'] . " - " . $item['price'] . "KM (Quantity: " . $item['quantity'] . ")";
        echo "<div class='quantity-buttons'>";
        echo "<form method='POST' style='display:inline;'>";
        echo "<input type='hidden' name='item_id' value='" . $item['itemID'] . "'>";
        echo "<input type='hidden' name='action' value='increase'>";
        echo "<button type='submit' class='increase-btn'>+</button>";
        echo "</form>";
        echo "<form method='POST' style='display:inline;'>";
        echo "<input type='hidden' name='item_id' value='" . $item['itemID'] . "'>";
        echo "<input type='hidden' name='action' value='decrease'>";
        echo "<button type='submit' class='decrease-btn'>-</button>";
        echo "</form>";
        echo "</div>";
        echo "</p>";
    }
    ?>
    <p class="total"><strong>TOTAL - <?php echo calculateTotal(); ?>KM</strong></p>
    <button class="checkout" onclick="location.href='checkout.php'">Go to Checkout</button>
</div>

<footer>
    <p>© 2024 ReSSSTaurant. All rights reserved.</p>
</footer>

<script>
    function toggleMenu() {
        document.querySelector('.nav-container').classList.toggle('active');
    }
</script>
</body>
</html>
