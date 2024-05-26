<?php
include "../authorization.php" ;
global $dbc;
checkUserRole('admin');

if (isset($_GET['staffID'])) {
    $id = $_GET['staffID'];
    $sql = "SELECT * FROM STAFF WHERE staffID = ?";
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
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $id ? 'Edit Staff Member' : 'Create New Staff Member'; ?></title>
    <link rel="stylesheet" href="form.css">
</head>
<body>
<div class="container">
    <h1><?php echo $id ? 'Edit Staff Member' : 'Create New Staff Member'; ?></h1>
    <form action="staff_form_submit.php" method="POST">
        <input type="hidden" name="staffID" value="<?php echo htmlspecialchars($id); ?>">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required><br>
        <label for="surname">Surname:</label>
        <input type="text" id="surname" name="surname" value="<?php echo htmlspecialchars($surname); ?>" required><br>
        <label for="dob">Date of Birth:</label>
        <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($dob); ?>" required><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required><br>
        <label for="password">Password (leave blank to keep current):</label>
        <input type="password" id="password" name="password"><br>
        <input type="submit" value="<?php echo $id ? 'Update Staff Member' : 'Create Staff Member'; ?>">
    </form>
</div>
</body>
</html>