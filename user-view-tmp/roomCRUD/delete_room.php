<?php
include 'proba.php';
global $dbc;

$roomID = $_GET['roomID'] ?? '';
if ($roomID) {
    $sql = "DELETE FROM ROOM WHERE roomID = ?";
    $stmt = $dbc->prepare($sql);
    if (!$stmt) {
        die("Error preparing statement: " . $dbc->error);
    }
    $stmt->bind_param("i", $roomID);

    if ($stmt->execute() === TRUE) {
        echo "Room deleted successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $dbc->close();

    header("Location: room_list.php");
    exit();
} else {
    echo "Room ID is missing.";
}
?>
