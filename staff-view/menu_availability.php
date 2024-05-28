<?php
include "../authorization.php" ;
checkUserRole('staff');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Menu Management</title>
    <link rel="stylesheet" href="menu_availability.css">
</head>
<body>
<h1>Staff Menu Management</h1>
<form action="process_availability.php" method="post">
    <table>
        <thead>
        <tr>
            <th>Item</th>
            <th>Availability</th>
        </tr>
        </thead>
        <tbody>
        <?php
        include ('db.php');
        global $dbc;

        // Fetch the menu items
        $sql = "SELECT itemID, name, available FROM ITEM";
        $result = $dbc->query($sql);

        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                $available = $row['available'] ? 'checked' : '';
                echo "<tr>
                                <td>{$row['name']}</td>
                                <td>
                                    <input type='checkbox' name='availability[{$row['itemID']}]' value='1' $available>
                                </td>
                              </tr>";
            }
        } else {
            echo "<tr><td colspan='2'>No menu items found.</td></tr>";
        }

        $dbc->close();
        ?>
        </tbody>
    </table>
    <br>
    <input type="submit" value="Update Availability">
</form>
</body>
</html>
