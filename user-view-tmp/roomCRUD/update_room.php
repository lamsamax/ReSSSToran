<?php
include 'proba.php';
global $dbc;

$roomID = $_GET['roomID'] ?? '';
if (!$roomID) {
    die("Missing room ID.");
}

$sql = "SELECT * FROM ROOM WHERE roomID = ?";
$stmt = $dbc->prepare($sql);
if (!$stmt) {
    die("Error preparing statement: " . $dbc->error);
}
$stmt->bind_param("i", $roomID);
$stmt->execute();
$result = $stmt->get_result();
$room = $result->fetch_assoc();

if (!$room) {
    die("Unknown room.");
}

$stmt->close();
$dbc->close();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Update Room</title>
</head>
<body>
<h2>Update Room</h2>
<form action="update_room_action.php" method="POST">
    <input type="hidden" name="roomID" value="<?php echo htmlspecialchars($roomID); ?>">
    <label for="roomNumber">Room Number:</label>
    <input type="number" name="roomNumber" id="roomNumber" required value="<?php echo htmlspecialchars($room['roomNumber']); ?>">
    <br>
    <label for="floor">Floor:</label>
    <input type="number" name="floor" id="floor" required value="<?php echo htmlspecialchars($room['floor']); ?>">
    <br>
    <input type="submit" value="Update">
</form>
</body>
</html>
