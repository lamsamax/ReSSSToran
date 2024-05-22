<?php
include '../mysqli_connect.php';
global $dbc;

$name = $_POST['name'];
$description = $_POST['description'];
$price = $_POST['price'];
$imageUrl = $_POST['imageUrl'];
$categoryID = $_POST['categoryID'];
$available = isset($_POST['available']) ? 1 : 0;

echo '<pre>';
print_r($_POST);
echo '</pre>';

$sql = "INSERT INTO ITEM (name, description, price, imageUrl, categoryID, available) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $dbc->prepare($sql);

if (!$stmt) {
    die("Error preparing statement: " . $dbc->error);
}

$stmt->bind_param("ssdsii", $name, $description, $price, $imageUrl, $categoryID, $available);

if ($stmt->execute() === TRUE) {
    echo "Menu item created successfully";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$dbc->close();

header("Location: item_list.php");
exit();

