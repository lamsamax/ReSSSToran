<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Menu Management</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .available {
            color: green;
        }
        .unavailable {
            color: red;
        }
    </style>
</head>
<body>
<h1>Staff Menu Management</h1>
<form action="update_availability.php" method="post">
    <table>
        <thead>
        <tr>
            <th>Item</th>
            <th>Availability</th>
        </tr>
        </thead>
        <tbody>
        <?php
        // Connect to the database
        $conn = new mysqli(  "152.67.94.35:3306",
            "root",
            "Password123/",
            "project");

        // Check the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch the menu items
        $sql = "SELECT itemid, name, available FROM ITEM";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                $available = $row['available'] ? 'checked' : '';
                echo "<tr>
                                <td>{$row['name']}</td>
                                <td>
                                    <input type='checkbox' name='availability[{$row['itemid']}]' value='1' {$available}>
                                </td>
                              </tr>";
            }
        } else {
            echo "<tr><td colspan='2'>No menu items found.</td></tr>";
        }

        $conn->close();
        ?>
        </tbody>
    </table>
    <br>
    <input type="submit" value="Update Availability">
</form>
</body>
</html>
