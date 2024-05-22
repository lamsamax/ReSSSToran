<?php
include('db.php');
global $dbc;

// Capture customer ID from the query parameter
$id = isset($_GET['staffID']) ? $_GET['staffID'] : '';

if ($id) {
    // Prepare the SQL delete statement
    $sql = "DELETE FROM STAFF WHERE staffID = ?";
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
    header("Location: staff_list.php");
    exit();
} else {
    echo "Staff ID is missing.";
}
?>
