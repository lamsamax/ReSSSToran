<?php
include "../authorization.php" ;
global $dbc;

checkUserRole('admin');

// Capture customer ID from the query parameter
$id = isset($_GET['customerID']) ? $_GET['customerID'] : '';

if ($id) {
    // Prepare the SQL delete statement
    $sql = "DELETE FROM CUSTOMER WHERE customerID = ?";
    $stmt = $dbc->prepare($sql);
    if (!$stmt) {
        die("Error preparing statement: " . $dbc->error);
    }
    $stmt->bind_param("i", $id);

    // Execute the statement and check for errors
    if ($stmt->execute() === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $dbc->close();

    // Redirect to the users list page
    header("Location: users_list.php");
    exit();
} else {
    echo "Customer ID is missing.";
}
?>
