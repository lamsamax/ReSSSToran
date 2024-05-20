<?php
include 'db.php';
global $dbc;
$id = '';
$name = '';
$surname = '';
$email = '';
$password = '';
$dob = '';
$role = '';
$isAdmin = '';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM CUSTOMER WHERE userID = ?";
    $stmt = $dbc->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $name = $user['name'];
        $surname = $user['surname'];
        $email = $user['mail'];
        $dob = $user['dob'];
        $role = $user['role'];
        $isAdmin = $user['isAdmin'];
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $id ? 'Edit User' : 'Create New User'; ?></title>
</head>
<body>
<h1><?php echo $id ? 'Edit User' : 'Create New User'; ?></h1>
<form action="submit_user.php" method="post">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <label for="name">Name:</label>
    <input type="text" name="name" id="name" required value="<?php echo $name; ?>">
    <br>
    <label for="surname">Surname:</label>
    <input type="text" name="surname" id="surname" required value="<?php echo $surname; ?>">
    <br>
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required value="<?php echo $email; ?>">
    <br>
    <label for="dob">Date of Birth:</label>
    <input type="date" name="dob" id="dob" required value="<?php echo $dob; ?>">
    <br>
    <label for="password">Password:</label>
    <input type="password" name="password" id="password" <?php echo $id ? '' : 'required'; ?>>
    <br>
    <label for="role">Role:</label>
    <select name="role" id="role" required>
        <option value="staff" <?php echo ($role == 'staff') ? 'selected' : ''; ?>>Staff</option>
        <option value="admin" <?php echo ($role == 'admin') ? 'selected' : ''; ?>>Admin</option>
        <option value="customer" <?php echo ($role == 'customer') ? 'selected' : ''; ?>>Customer</option>
    </select>
    <br>
    <label for="isAdmin">Is Admin:</label>
    <input type="checkbox" name="isAdmin" id="isAdmin" value="1" <?php echo ($isAdmin) ? 'checked' : ''; ?>>
    <br>
    <input type="submit" value="Submit">
</form>
</body>
</html>

<?php
$dbc->close();
?>
