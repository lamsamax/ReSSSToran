<?php
include 'db.php';
global $dbc;

$id = isset($_GET['customerID']) ? $_GET['customerID'] : '';
if (!$id) {
    die("Customer ID is missing.");
}

$sql = "SELECT * FROM CUSTOMER WHERE customerID = ?";
$stmt = $dbc->prepare($sql);
if (!$stmt) {
    die("Error preparing statement: " . $dbc->error);
}
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();

if (!$customer) {
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
<form action="update_customer.php" method="POST">
    <input type="hidden" name="customerID" value="<?php echo htmlspecialchars($id); ?>">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($customer['name']); ?>" required><br>
    <label for="surname">Surname:</label>
    <input type="text" id="surname" name="surname" value="<?php echo htmlspecialchars($customer['surname']); ?>" required><br>
    <label for="dob">Date of Birth:</label>
    <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($customer['dob']); ?>" required><br>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($customer['mail']); ?>" required><br>
    <label for="password">Password (leave blank to keep current):</label>
    <input type="password" id="password" name="password"><br>
    <label for="role">Role:</label>
    <input type="text" id="role" name="role" value="<?php echo htmlspecialchars($customer['role']); ?>" required><br>
    <label for="isAdmin">Is Admin:</label>
    <input type="checkbox" id="isAdmin" name="isAdmin" <?php echo $customer['isAdmin'] ? 'checked' : ''; ?>><br>
    <input type="submit" value="Update Customer">
</form>
</body>
</html>


