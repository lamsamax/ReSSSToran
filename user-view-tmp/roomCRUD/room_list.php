<?php
include 'proba.php';
global $dbc;

$sql = "SELECT roomID, roomNumber, floor FROM ROOM";
$result = $dbc->query($sql);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Room List</title>
</head>
<body>
<h1>Rooms</h1>
<a href="create_room.php">Create New Room</a>
<table border="1">
    <thead>
    <tr>
        <th>ID</th>
        <th>Room Number</th>
        <th>Floor</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['roomID']}</td>
                <td>{$row['roomNumber']}</td>
                <td>{$row['floor']}</td>
                <td>
                    <a href='update_room.php?roomID={$row['roomID']}'>Update</a>
                    <a href='delete_room.php?roomID={$row['roomID']}'>Delete</a>
                </td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='4'>No rooms found.</td></tr>";
    }
    ?>
    </tbody>
</table>
</body>
</html>

<?php
$dbc->close();
?>
