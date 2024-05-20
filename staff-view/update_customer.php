<?php
include 'db.php';
global $dbc;

// Capture form data
$id = isset($_POST['customerID']) ? $_POST['customerID'] : '';
$name = $_POST['name'];
$surname = $_POST['surname'];
$dob = $_POST['dob'];
$email = $_POST['email'];
//ENCRYPTED PASSWORD
//$password = isset($_POST['password']) && !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : '';
//UNENCRYPTED
$password = isset($_POST['password']) && !empty($_POST['password']) ? $_POST['password'] : '';
$role = $_POST['role'];
$isAdmin = isset($_POST['isAdmin']) ? 1 : 0; // Default to 0 if not set

// Debugging output
echo '<pre>';
print_r($_POST);
echo '</pre>';

if ($id) {
    // Update user
    if ($password) {
        $sql = "UPDATE CUSTOMER SET name=?, surname=?, dob=?, mail=?, password=?, role=?, isAdmin=? WHERE customerID=?";
        $stmt = $dbc->prepare($sql);
        if (!$stmt) {
            die("Error preparing statement: " . $dbc->error);
        }
        $stmt->bind_param("ssssssii", $name, $surname, $dob, $email, $password, $role, $isAdmin, $id);
    } else {
        $sql = "UPDATE CUSTOMER SET name=?, surname=?, dob=?, mail=?, role=?, isAdmin=? WHERE customerID=?";
        $stmt = $dbc->prepare($sql);
        if (!$stmt) {
            die("Error preparing statement: " . $dbc->error);
        }
        $stmt->bind_param("ssssssi", $name, $surname, $dob, $email, $role, $isAdmin, $id);
    }

    // Execute the statement and check for errors
    if ($stmt->execute() === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $dbc->close();
    header("Location: users_list.php");
    exit();
} else {
    echo "Customer ID is missing.";
}
?>
