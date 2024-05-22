<?php
include '../mysqli_connect.php';
global $dbc;
$id = '';
$name = '';
$description = '';

if (isset($_GET['categoryID'])) {
    $id = $_GET['categoryID'];
    $sql = "SELECT * FROM CATEGORY WHERE categoryID = ?";
    $stmt = $dbc->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $category = $result->fetch_assoc();
        $name = $category['name'];
        $description = $category['description'];
    }
    $stmt->close();
}
?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $id ? 'Edit Category' : 'Create New Category'; ?></title>
    </head>
    <body>
    <h1><?php echo $id ? 'Edit Category' : 'Create New Category'; ?></h1>
    <form action="create_category_action.php" method="post">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required value="<?php echo $name; ?>">
        <br>
        <label for="description">Description:</label>
        <input type="text" name="description" id="description" required value="<?php echo $description; ?>">
        <br>
        <input type="submit" value="Submit">
    </form>
    </body>
    </html>

<?php
$dbc->close();
?>