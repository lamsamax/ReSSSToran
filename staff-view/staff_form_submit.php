<?php
include "../authorization.php" ;
global $dbc;
checkUserRole('admin');
// Capture form data
$id = isset($_POST['staffID']) ? $_POST['staffID'] : '';
$name = $_POST['name'];
$surname = $_POST['surname'];
$dob = $_POST['dob'];
$email = $_POST['email'];
//ENCRYPTED PASSWORD
//$password = isset($_POST['password']) && !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : '';
//UNENCRYPTED
$password = isset($_POST['password']) && !empty($_POST['password']) ? $_POST['password'] : '';

// Debugging output
echo '<pre>';
print_r($_POST);
echo '</pre>';

if ($id) {
    // Update user
    if ($password) {
        $sql = "UPDATE STAFF SET name=?, surname=?, dob=?, mail=?, password=? WHERE staffID=?";
        $stmt = $dbc->prepare($sql);
        if (!$stmt) {
            die("Error preparing statement: " . $dbc->error);
        }
        $stmt->bind_param("sssssi", $name, $surname, $dob, $email, $password, $id);
        echo "Updating user with password.<br>";
        echo "SQL: $sql<br>";
        echo "Params: $name, $surname, $dob, $email, $password, $id<br>";
    } else {
        $sql = "UPDATE STAFF SET name=?, surname=?, dob=?, mail=? WHERE staffID=?";
        $stmt = $dbc->prepare($sql);
        if (!$stmt) {
            die("Error preparing statement: " . $dbc->error);
        }
        $stmt->bind_param("ssssi", $name, $surname, $dob, $email, $id);
        echo "Updating user without password.<br>";
        echo "SQL: $sql<br>";
        echo "Params: $name, $surname, $dob, $email, $id<br>";
    }
    } else {
    // Create user
    $sql = "INSERT INTO STAFF (name, surname, dob, mail, password) VALUES (?, ?, ?, ?, ?)";
    $stmt = $dbc->prepare($sql);
    if (!$stmt) {
        die("Error preparing statement: " . $dbc->error);
    }
    $stmt->bind_param("sssss", $name, $surname, $dob, $email, $password);
}

    // Execute the statement and check for errors
    if ($stmt->execute() === TRUE) {
        echo "Record updated successfully<br>";
    } else {
        echo "Error: " . $stmt->error . "<br>";
    }

    // Close the statement and connection
    $stmt->close();
    $dbc->close();
    // Redirect after processing
    header("Location: staff_list.php");
    exit();
?>
