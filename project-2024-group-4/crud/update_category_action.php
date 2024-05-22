<?php
include '../mysqli_connect.php';
global $dbc;

// Capture form data
$id = $_POST['categoryID'];
$name = $_POST['name'];
$description = $_POST['description'];

echo '<pre>';
print_r($_POST);
echo '</pre>';

$sql = "UPDATE CATEGORY SET name=?, description=? WHERE categoryID=?";
$stmt = $dbc->prepare($sql);

if (!$stmt) {
    die("Error preparing statement: " . $dbc->error);
}

$stmt->bind_param("ssi", $name, $description, $id);

if ($stmt->execute() === TRUE) {
    echo "Category created successfully";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$dbc->close();

header("Location: category_list.php");
exit();

