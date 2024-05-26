<?php
include "../authorization.php" ;
global $dbc;

checkUserRole('admin');

$sql = "SELECT staffID, name, surname, dob, mail, password FROM STAFF";
$result = $dbc->query($sql);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff List</title>
    <link rel="stylesheet" href="list.css">
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Staff List</h1>
        <a class="create-form" href="staff_form.php">Create New Staff Member</a>
    </div>
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Surname</th>
            <th>Date of Birth</th>
            <th>Email</th>
            <th>Password</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if($result->num_rows > 0){
            while ($row = $result->fetch_assoc()){
                echo " <tr>
                        <td>{$row['staffID']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['surname']}</td>
                        <td>{$row['dob']}</td>
                        <td>{$row['mail']}</td>
                        <td>{$row['password']}</td>
                        <td>
                        <a class='button edit-btn' href='staff_form.php?staffID={$row['staffID']}'>Edit</a>
                        <a class ='button delete-btn' href='staff_delete.php?staffID={$row['staffID']}'>Delete</a>
                        </td>
                       </tr>";
            }
        } else {echo "
        <tr><td colspan='8'>No users found</td></tr>";
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
