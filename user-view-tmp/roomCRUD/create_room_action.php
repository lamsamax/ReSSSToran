<?php
include '../../user-view-tmp/html-php/proba.php';
global $dbc;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $roomNumber = $_POST['roomNumber'];
    $floor = $_POST['floor'];

    $sql = "INSERT INTO ROOM (roomNumber, floor) VALUES (?, ?)";
    $stmt = $dbc->prepare($sql);
    $stmt->bind_param("ii", $roomNumber, $floor);

    if ($stmt->execute()) {
        echo "New room created successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $dbc->error;
    }

    $stmt->close();
}

$dbc->close();
?>
