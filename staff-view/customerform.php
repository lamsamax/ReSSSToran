<?php
include('db.php');
global $dbc;

$id = '';
$name = '';
$surname = '';
$email = '';
$password = '';
$dob = '';
$role = '';
$isAdmin = '';

if (isset($_GET['customerID'])) {
    $id = $_GET['customerID'];
    $sql = "SELECT * FROM CUSTOMER WHERE customerID = ?";
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
    <link rel="stylesheet" href="form.css">
</head>
<body>
<div class="container">
    <h1><?php echo $id ? 'Edit User' : 'Create New User'; ?></h1>
    <form action="customer_form_submit.php" method="POST">
        <input type="hidden" name="customerID" value="<?php echo htmlspecialchars($id); ?>">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
        <label for="surname">Surname:</label>
        <input type="text" id="surname" name="surname" value="<?php echo htmlspecialchars($surname); ?>" required>
        <label for="dob">Date of Birth:</label>
        <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($dob); ?>" required>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
        <label for="password">Password (leave blank to keep current):</label>
        <input type="password" id="password" name="password">
        <label for="role">Role:</label>
        <select name="role" id="role" required>
            <option value="professor" <?php echo htmlspecialchars($role == 'professor') ? 'selected' : ''; ?>>Professor</option>
            <option value="admin" <?php echo htmlspecialchars($role == 'admin') ? 'selected' : ''; ?>>Admin</option>
            <option value="student" <?php echo htmlspecialchars($role == 'student') ? 'selected' : ''; ?>>Student</option>
        </select>
        <div class="checkbox-label">
            <input type="checkbox" name="isAdmin" id="isAdmin" value="1" <?php echo htmlspecialchars($isAdmin) ? 'checked' : ''; ?>>
            <label for="isAdmin">Is Admin</label>
        </div>
        <input type="submit" value="<?php echo $id ? 'Update Customer' : 'Create Customer'; ?>">
    </form>
</div>
</body>
</html>
