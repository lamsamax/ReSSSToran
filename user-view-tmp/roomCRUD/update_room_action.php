<?php
include '../../user-view-tmp/html-php/proba.php';
global $dbc;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $roomID = $_POST['roomID'];
    $roomNumber = $_POST['roomNumber'];
    $floor = $_POST['floor'];

    $sql = "UPDATE ROOM SET roomNumber = ?, floor = ? WHERE roomID = ?";
    $stmt = $dbc->prepare($sql);
    if (!$stmt) {
        die("Error preparing statement: " . $dbc->error);
    }
    $stmt->bind_param("iii", $roomNumber, $floor, $roomID);

    if ($stmt->execute()) {
        echo "Room updated successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $dbc->close();

    header("Location: room_list.php");
    exit();
}
?>
