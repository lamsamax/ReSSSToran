<?php
include '../mysqli_connect.php';
global $dbc;

$sql = "SELECT categoryID, name, description FROM CATEGORY";
$result = $dbc->query($sql);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Category List</title>
</head>
<body>
<h1>Categories</h1>
<a href="create_category_form.php">Create New Category</a>
<table border="1">
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Description</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['categoryID']}</td>
                <td>{$row['name']}</td>
                <td>{$row['description']}</td>
                <td>
                    <a href='update_category_form.php?categoryID={$row['categoryID']}'>Update</a>
                    <a href='delete_category.php?categoryID={$row['categoryID']}'>Delete</a>
                </td>
            </tr>";
        }
    }
    else  {
        echo "<tr><td colspan='4'>No categories found.</td></tr>";
    }
    ?>
    </tbody>
</table>
</body>
</html>

<?php
$dbc->close();
?>