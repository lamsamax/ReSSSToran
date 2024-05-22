<?php
include '../mysqli_connect.php';
global $dbc;

$id = $_GET['itemID'] ?? '';
if ($id) {
    $sql = "DELETE FROM ITEM WHERE itemID = ?";
    $stmt = $dbc->prepare($sql);
    if (!$stmt) {
        die("Error preparing statement: " . $dbc->error);
    }
    $stmt->bind_param("i", $id);

    if ($stmt->execute() === TRUE) {
        echo "Item deleted successfully.";
    }
    else echo "Error: " . $stmt->error;
    $stmt->close();
    $dbc->close();

    header("Location: item_list.php");
    exit();
}
else {
    echo "Item ID is missing.";
}