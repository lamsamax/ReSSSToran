<?php
include '../mysqli_connect.php';
global $dbc;

$id = $_GET['categoryID'] ?? '';
if ($id) {
    $sql = "DELETE FROM CATEGORY WHERE categoryID = ?";
    $stmt = $dbc->prepare($sql);
    if (!$stmt) {
        die("Error preparing statement: " . $dbc->error);
    }
    $stmt->bind_param("i", $id);

    if ($stmt->execute() === TRUE) {
        echo "Category deleted successfully";
    }
    else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $dbc->close();

    header("Location: category_list.php");
    exit();
}

else {
    echo "Category ID is missing.";
}
