<?php
include ('db.php');
global $dbc;

$sql = "SELECT staffID, name, surname, dob, mail, password FROM STAFF";
$result = $dbc->query($sql);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Staff List</title>
</head>
<body>
<h1>Staff List<h1>
        <a href="staff_form.php">Create New Staff Member</a>
        <table border="1">
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
                    <a href='edit_staff.php?staffID={$row['staffID']}'>Edit</a>
                    <a href='delete_staff.php?staffID={$row['staffID']}'>Delete</a>
                    </td>
                   </tr>";
        }
    } else {echo "
    <tr><td colspan='8'>No users found</td><</tr>";
    }
    ?>
    </tbody>
</table>
</body>
</html>

<?php
$dbc->close();
?>
