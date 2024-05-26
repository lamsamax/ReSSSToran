<?php
include ('../../authorization.php');
checkUserRole('admin');
global $dbc;

$sql = "SELECT itemID, name, price, description, imageUrl, categoryID, available FROM ITEM";
$result = $dbc->query($sql);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Item List</title>
    <link rel="stylesheet" href="../css/liststyle.css"
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Items</h1>
        <a class="create-form" href="create_item_form.php">Create New Item</a>
    </div>
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Price</th>
        <th>Description</th>
        <th>Image URL</th>
        <th>Category</th>
        <th>Availability</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['itemID']}</td>
                <td>{$row['name']}</td>
                <td>{$row['price']}</td>
                <td>{$row['description']}</td>
                <td>{$row['imageUrl']}</td>
                <td>{$row['categoryID']}</td>
                <td>" . ($row['available'] ? 'Available' : 'Unavailable') . "</td>
                <td class='actions'>
                    <a class='button edit-btn' href='update_item_form.php?itemID={$row['itemID']}'>Update</a>
                    <a class='button delete-btn' href='delete_item.php?itemID={$row['itemID']}'>Delete</a>
                </td>
            </tr>";
        }
    }
    else  {
        echo "<tr><td colspan='8'>No items found.</td></tr>";
    }
    ?>
    </tbody>
</table>
</div>
</body>
</html>

<?php
$dbc->close();
?>
