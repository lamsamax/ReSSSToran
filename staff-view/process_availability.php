<?php
include "../authorization.php" ;
global $dbc;

checkUserRole('staff');
// Debugging: Check if form data is being received
echo '<pre>';
print_r($_POST);
echo '</pre>';

// Reset all items to unavailable
$sql = "UPDATE ITEM SET available = 0";
if (!$dbc->query($sql)) {
    die("Error updating availability: " . $dbc->error);
}

// Update availability based on form submission
if (isset($_POST['availability'])) {
    foreach ($_POST['availability'] as $id => $value) {
        $sql = "UPDATE ITEM SET available = 1 WHERE itemID = ?";
        $stmt = $dbc->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $id);
            if (!$stmt->execute()) {
                echo "Error executing statement for id $id: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error preparing statement for id $id: " . $dbc->error;
        }
    }
} else {
    echo "No availability data received.";
}

// Close the connection
$dbc->close();

// Redirect back to the update form
header("Location: update_availability.php");
exit();
?>
