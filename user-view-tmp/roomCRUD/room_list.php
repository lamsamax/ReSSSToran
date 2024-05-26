<?php
include '../../user-view-tmp/html-php/proba.php';
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
    <link rel="stylesheet" href="../../user-view-tmp/CSS/roomlist.css">
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Rooms</h1>
        <a href="create_room.php" class="button">Create New Room</a>
    </div>
    <div class="table-container">
        <table>
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
                        <td class='table-buttons'>
                            <a href='update_room.php?roomID={$row['roomID']}' class='button edit-btn'>Update</a>
                            <a href='delete_room.php?roomID={$row['roomID']}' class='button delete-btn'>Delete</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No rooms found.</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

<?php
$dbc->close();
?>
