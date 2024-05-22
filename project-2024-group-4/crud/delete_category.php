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

    // Execute the statement and check for errors
    if ($stmt->execute() === TRUE) {
        echo "Category deleted successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $dbc->close();

    // Redirect to the category list page
    header("Location: category_list.php");
    exit();
} else {
    echo "Category ID is missing.";
}
