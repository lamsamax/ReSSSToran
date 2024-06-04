<?php
global $dbc;

include '../../authorization.php';
checkUserRole('admin');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
header("Location: ../../project-2024-group-4/php/login.php");
exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$sql = "SELECT * FROM CUSTOMER WHERE customerID = ?";
$stmt = $dbc->prepare($sql);
if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $name = htmlspecialchars($user['name']);
        $surname = htmlspecialchars($user['surname']);
        $dob = htmlspecialchars($user['dob']);
        $role = htmlspecialchars($user['role']);
        $mail = htmlspecialchars($user['mail']);

        $dobFormatted = (new DateTime($dob))->format('d/m/Y');
    } else {
        echo "No user found with this ID.";
        exit();
    }
    $stmt->close();
} else {
echo "Error preparing statement: " . $dbc->error;
exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<link href="https://fonts.googleapis.com/css2?family=Amatic+SC:wght@400;700&display=swap" rel="stylesheet">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReSSSToran</title>
    <link rel="stylesheet" href="../CSS/profile.css">
</head>
<body>
<div id="grad">
    <div class="sidebar">
        <div class="header">
            <h1>ReSSSToran</h1>
            <div id="profile-info" class="profile-info">
                <p><strong>Name:</strong> <?php echo $name; ?></p>
                <p><strong>Surname:</strong> <?php echo $surname; ?></p>
                <p><strong>Date of Birth:</strong> <?php echo $dobFormatted; ?></p>
                <p><strong>Role:</strong> <?php echo $role; ?></p>
                <p><strong>Email:</strong> <?php echo $mail; ?></p>
                <button type="button" id="editProfileBtn" class="edit-btn">Edit Profile</button>
                <button type="button" id="changePasswordBtn" class="edit-btn">Change Password</button>
                <button onclick="window.location.href='logout.php'" class="edit-btn">Logout</button>
            </div>
            <form id="profile-form" class="profile-form" action="update_profile.php" method="post" style="display:none;">
                <div class="form-field">
                    <label for="dob">Date of Birth</label>
                    <input type="date" id="dob" name="dob" value="<?php echo $dob; ?>" required>
                </div>
                <button type="submit" class="edit-btn">Save Changes</button>
                <button type="button" id="cancelEditBtn" class="edit-btn">Cancel</button>
            </form>
    </div>
    <div class="buttons">
        <button onclick="window.location.href='../../staff-view/users_list.php'">Customers</button>
        <button onclick="window.location.href='../../staff-view/staff_list.php'">Staff</button>
        <button onclick="window.location.href='../../project-2024-group-4/crud/category_list.php'">Categories</button>
        <button onclick="window.location.href='../../project-2024-group-4/crud/item_list.php'">Items</button>
        <button onclick="window.location.href='stats.php'">Statistics</button>
        <button onclick="window.location.href='../../staff-view/admin_order_history.php'">Order history</button>
        <button onclick="window.location.href=''">Reviews</button>
    </div>
</div>
    <div class="image">
        <img src="../pics/16558127774-ssst-kampus.jpg" alt="Image Description">
    </div>
</body>
</html>
