<?php
include ('../../authorization.php');
checkUserRole('admin');
global $dbc;

$id = isset($_GET['categoryID']) ? $_GET['categoryID'] : '';
if (!$id) {
    die("Missing category ID.");
}

$sql = "SELECT * FROM CATEGORY WHERE categoryID = ?";
$stmt = $dbc->prepare($sql);
if (!$stmt) {
    die("Error preparing statement: " . $dbc->error);
}
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$category = $result->fetch_assoc();

if (!$category) {
    die("Unknown category.");
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
    <title>Update Category</title>
</head>
<body>
<div class="container">
    <h2>Update Category</h2>
    <form action="update_category_action.php" method="POST">
        <input type="hidden" name="categoryID" value="<?php echo htmlspecialchars($id); ?>">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($category['name']); ?>" required><br>
        <label for="description">Description:</label>
        <input type="text" id="description" name="description" value="<?php echo htmlspecialchars($category['description']); ?>" required><br>
        <input type="submit" value="Update Category">
    </form>
</div>
</body>
</html>


