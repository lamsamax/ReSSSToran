<?php
include ('../../authorization.php');
checkUserRole('admin');
global $dbc;

$id = $_GET['itemID'] ?? '';
if (!$id) {
    die("Missing category ID.");
}

$sql = "SELECT * FROM ITEM WHERE itemID = ?";
$stmt = $dbc->prepare($sql);
if (!$stmt) {
    die("Error preparing statement: " . $dbc->error);
}
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$item = $result->fetch_assoc();

if (!$item) {
    die("Unknown item.");
}

$sql = "SELECT categoryID, name FROM CATEGORY";
$result = $dbc->query($sql);
$categories = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
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
    <link rel="stylesheet" href="../css/formstyle.css">
    <title>Update Item</title>
</head>
<body>
<div class="container">
    <h2>Update Item</h2>
    <form action="update_item_action.php" method="POST">
        <input type="hidden" name="itemID" value="<?php echo htmlspecialchars($id); ?>">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required value="<?php echo htmlspecialchars($item['name']); ?>">
        <br>
        <label for="description">Description:</label>
        <input type="text" name="description" id="description" required value="<?php echo htmlspecialchars($item['description']); ?>">
        <br>
        <label for="price">Price:</label>
        <input type="number" step="0.01" name="price" id="price" required value="<?php echo htmlspecialchars($item['price']); ?>">
        <br>
        <label for="imageUrl">Image URL:</label>
        <input type="text" name="imageUrl" id="imageUrl" value="<?php echo htmlspecialchars($item['imageUrl']); ?>">
        <br>
        <label for="categoryID">Category:</label>
        <select name="categoryID" id="categoryID" required>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo htmlspecialchars($category['categoryID']); ?>" <?php echo $category['categoryID'] == $item['categoryID'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($category['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="available">Available:</label>
        <input type="checkbox" name="available" id="available" <?php echo $item['available'] ? 'checked' : ''; ?>>
        <br>
        <input type="submit" value="Update">
    </form>
</div>
</body>
</html>


