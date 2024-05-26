<?php
include '../mysqli_connect.php';
global $dbc;

$sql = "SELECT categoryID, name FROM CATEGORY";
$result = $dbc->query($sql);
$categories = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Item</title>
    <link rel="stylesheet" href="../css/formstyle.css">
</head>
<body>
<div class="container">
    <h1>Create Item</h1>
    <form action="create_item_action.php" method="post">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required>
        <br>
        <label for="description">Description:</label>
        <input type="text" name="description" id="description" required>
        <br>
        <label for="price">Price:</label>
        <input type="number" step="0.01" name="price" id="price" required>
        <br>
        <label for="imageUrl">Image URL:</label>
        <input type="text" name="imageUrl" id="imageUrl">
        <br>
        <label for="categoryID">Category:</label>
        <select name="categoryID" id="categoryID" required>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo htmlspecialchars($category['categoryID']); ?>">
                    <?php echo htmlspecialchars($category['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="available">Available:</label>
        <input type="checkbox" name="available" id="available">
        <br>
        <input type="submit" value="Submit">
    </form>
</div>
</body>
</html>

<?php
$dbc->close();
?>
