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
    <link rel="stylesheet" href="list.css">
</head>
<body>
<div class="container">
    <div class="header">
        <h1>User List</h1>
        <a href="customerform.php" class="button">Create New User</a>
    </div>
    <table>
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
                        <td class='table-buttons'>
                            <a href='customerform.php?customerID={$row['customerID']}' class='button edit-btn'>Edit</a>
                            <a href='delete_customer.php?customerID={$row['customerID']}' class='button delete-btn'>Delete</a>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='8'>No users found</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>
</body>
</html>

<?php
$dbc->close();
?>
