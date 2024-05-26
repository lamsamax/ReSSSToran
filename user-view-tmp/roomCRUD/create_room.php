<?php
include '../../user-view-tmp/html-php/proba.php';
global $dbc;

$sql = "SELECT roomID, roomNumber, floor FROM ROOM";
$result = $dbc->query($sql);
$rooms = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../staff-view/form.css">
    <title>Create Room</title>
</head>
<body>
<div class="container">
    <h1>Create Room</h1>
    <form action="create_room_action.php" method="post">
        <label for="roomNumber">Room Number:</label>
        <input type="number" name="roomNumber" id="roomNumber" required>
        <br>
        <label for="floor">Floor:</label>
        <input type="number" name="floor" id="floor" required>
        <br>
        <input type="submit" value="Submit">
    </form>
</div>
</body>
</html>

<?php
$dbc->close();
?>
