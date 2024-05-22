<?php
include '../mysqli_connect.php';
global $dbc;

$id = $_POST['itemID'];
$name = $_POST['name'];
$description = $_POST['description'];
$price = $_POST['price'];
$imageUrl = $_POST['imageUrl'];
$categoryID = $_POST['categoryID'];
$available = isset($_POST['available']) ? 1 : 0;

echo '<pre>';
print_r($_POST);
echo '</pre>';

$sql = "UPDATE ITEM SET name=?, description=?, price=?, imageUrl=?, categoryID=?, available=? WHERE itemID=?";
$stmt = $dbc->prepare($sql);

if (!$stmt) {
    die("Error preparing statement: " . $dbc->error);
}

$stmt->bind_param("ssdsiii", $name, $description, $price, $imageUrl, $categoryID, $available, $id);

if ($stmt->execute() === TRUE) {
    echo "Menu item updated successfully";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$dbc->close();

header("Location: item_list.php");
exit();


