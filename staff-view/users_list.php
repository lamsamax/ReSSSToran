<?php
include 'db.php';
global $dbc;

$sql = "SELECT customerID, name, surname, dob, mail, role, isAdmin FROM CUSTOMER";
$result = $dbc->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
</head>
<body>
<h1>User List</h1>
<a href="form.php">Create New User</a>
<table border="1">
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Surname</th>
        <th>Date of Birth</th>
        <th>Email</th>
        <th>Role</th>
        <th>Is Admin</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                            <td>{$row['customerID']}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['surname']}</td>
                            <td>{$row['dob']}</td>
                            <td>{$row['mail']}</td>
                            <td>{$row['role']}</td>
                            <td>" . ($row['isAdmin'] ? 'Yes' : 'No') . "</td>
                            <td>
                                <a href='edit_customer_form.php?customerID={$row['customerID']}'>Edit</a>
                                <a href='delete_customer.php?customerID={$row['customerID']}'>Delete</a>
                            </td>
                          </tr>";
        }
    } else {
        echo "<tr><td colspan='8'>No users found</td></tr>";
    }
    ?>
    </tbody>
</table>
</body>
</html>

<?php
$dbc->close();
?>
