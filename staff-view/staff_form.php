<?php
include ('db.php');
global $dbc;

$id = isset($_GET['staffID']) ? $_GET['staffID'] : '';
if (!$id) {
    die("Staff ID is missing.");
}

    $sql = "SELECT * FROM STAFF WHERE staffID = ?";
    $stmt = $dbc->prepare($sql);
if (!$stmt) {
    die("Error preparing statement: " . $dbc->error);
}
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$staffmember = $result->fetch_assoc();

if (!$staffmember) {
    die("Customer not found.");
}

// Close the statement and connection
$stmt->close();
$dbc->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Customer</title>
</head>
<body>
<h2>Update Customer</h2>
<form action="staff_form_submit.php" method="POST">
    <input type="hidden" name="staffID" value="<?php echo htmlspecialchars($id); ?>">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($staffmember['name']); ?>" required><br>
    <label for="surname">Surname:</label>
    <input type="text" id="surname" name="surname" value="<?php echo htmlspecialchars($staffmember['surname']); ?>" required><br>
    <label for="dob">Date of Birth:</label>
    <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($staffmember['dob']); ?>" required><br>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($staffmember['mail']); ?>" required><br>
    <label for="password">Password (leave blank to keep current):</label>
    <input type="password" id="password" name="password"><br>
    <input type="submit" value="Update Staff Member">
</form>
</body>
</html>


