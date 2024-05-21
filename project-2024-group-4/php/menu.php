<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../mysqli_connect.php');
session_start();

if (!isset($_SESSION['order'])) {
    $_SESSION['order'] = [];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_bag'])) {
    $itemName = $_POST['item_name'];
    $itemPrice = $_POST['item_price'];

    $_SESSION['order'][] = [
        'name' => $itemName,
        'price' => $itemPrice,
    ];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove_item'])) {
    $itemIndex = $_POST['item_index'];
    unset($_SESSION['order'][$itemIndex]);
    $_SESSION['order'] = array_values($_SESSION['order']); // Re-index the array
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="../css/menustyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Menu</title>
</head>
<body>
<button onclick="window.location.href='home.html'" class="home-button">
    <i class="fas fa-home"></i>
</button>

<nav>
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
</nav>

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

    $items_result = mysqli_query($dbc, "SELECT * FROM ITEM WHERE categoryID=" . $category['categoryID']);
    if (!$items_result) {
        die('Query failed: ' . mysqli_error($dbc));
    }
    while ($item = mysqli_fetch_assoc($items_result)) {
        echo "<div class='item'>";
        echo "<img src='../" . $item['imageUrl'] . "' alt='" . $item['name'] . "' class='item-image'>";
        echo "<div class='item-details'>";
        echo "<p>" . $item['name'] . " - " . $item['price'] . "KM</p>";
        echo "<p class='item-description'>" . $item['description'] . "</p>";
        echo "<form method='POST'>";
        echo "<input type='hidden' name='item_name' value='" . $item['name'] . "'>";
        echo "<input type='hidden' name='item_price' value='" . $item['price'] . "'>";
        echo "<button type='submit' name='add_to_bag'>Add to bag</button>";
        echo "</form>";
        echo "</div></div>";
    }

    echo "</section>";
}
?>

<div class="order-summary">
    <h1>YOUR ORDER</h1>
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
    <button class="checkout" onclick="location.href='checkout.php'">Go to Checkout</button>
</div>

<footer>
    <p>© 2024 ReSSSTaurant. All rights reserved.</p>
</footer>
</body>
</html>

