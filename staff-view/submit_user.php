<?php
include 'db.php';
global $dbc;

// Capture form data
$id = isset($_POST['id']) ? $_POST['id'] : '';
$name = $_POST['name'];
$surname = $_POST['surname'];
$dob = $_POST['dob'];
$email = $_POST['email'];
$password = isset($_POST['password']) && !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : '';
$role = $_POST['role'];
$isAdmin = isset($_POST['isAdmin']) ? 1 : 0;

// Debugging output
echo '<pre>';
print_r($_POST);
echo '</pre>';

if ($id) {
    // Update user
    if ($password) {
        $sql = "UPDATE CUSTOMER SET name=?, surname=?, dob=?, mail=?, password=?, role=?, isAdmin=? WHERE userID=?";
        $stmt = $dbc->prepare($sql);
        if (!$stmt) {
            die("Error preparing statement: " . $dbc->error);
        }
        $stmt->bind_param("ssssssii", $name, $surname, $dob, $email, $password, $role, $isAdmin, $id);
    } else {
        $sql = "UPDATE CUSTOMER SET name=?, surname=?, dob=?, mail=?, role=?, isAdmin=? WHERE userID=?";
        $stmt = $dbc->prepare($sql);
        if (!$stmt) {
            die("Error preparing statement: " . $dbc->error);
        }
        $stmt->bind_param("ssssssi", $name, $surname, $dob, $email, $role, $isAdmin, $id);
    }
} else {
    // Create user
    $sql = "INSERT INTO CUSTOMER (name, surname, dob, mail, password, role, isAdmin) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $dbc->prepare($sql);
    if (!$stmt) {
        die("Error preparing statement: " . $dbc->error);
    }
    $stmt->bind_param("ssssssi", $name, $surname, $dob, $email, $password, $role, $isAdmin);
}

// Execute the statement and check for errors
if ($stmt->execute() === TRUE) {
    echo "Record updated/created successfully";
} else {
    echo "Error: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$dbc->close();
header("Location: users_list.php");
exit();
?>
