<?php
include 'db.php';
global $dbc;

$sql = "SELECT userID, name, surname, dob, mail, role, isAdmin FROM CUSTOMER";
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
                            <td>{$row['userID']}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['surname']}</td>
                            <td>{$row['dob']}</td>
                            <td>{$row['mail']}</td>
                            <td>{$row['role']}</td>
                            <td>" . ($row['isAdmin'] ? 'Yes' : 'No') . "</td>
                            <td>
                                <a href='form.php?id={$row['userID']}'>Edit</a>
                                <a href='delete_user.php?id={$row['userID']}'>Delete</a>
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
