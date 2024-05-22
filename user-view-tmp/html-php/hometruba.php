<?php
// Start session
global $dbc;
session_start();
include 'proba.php'; // Adjust this path as needed

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../project-2024-group-4/php/login.php"); // Ensure this path is correct
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
        </div>
        <div class="sidebar-content">
            <p>
                Welcome Truba
            </p>
        </div>
        <div id="profile-info">
            <p><strong>Name:</strong> <?php echo $name; ?></p>
            <p><strong>Surname:</strong> <?php echo $surname; ?></p>
            <p><strong>Date of Birth:</strong> <?php echo $dob; ?></p>
            <p><strong>Role:</strong> <?php echo $role; ?></p>
            <p><strong>Email:</strong> <?php echo $mail; ?></p>
            <button type="button" id="editProfileBtn" class="edit-btn">Edit Profile</button>
        </div>
        <form id="profile-form" class="profile-form" action="update_profile.php" method="post" style="display:none;">
            <div class="form-field">
                <label for="dob">Date of Birth</label>
                <input type="date" id="dob" name="dob" value="<?php echo $dob; ?>" required>
            </div>
            <button type="submit" class="edit-btn">Save Changes</button>
            <button type="button" id="cancelEditBtn" class="edit-btn">Cancel</button>
        </form>
        <button type="button" id="changePasswordBtn" class="edit-btn">Change Password</button>
    </div>
/* ovdje implement connection to database, trubin username, password, da može promijeniti tj edit profile button
    <div class="buttons">
        <button onclick="window.location.href=''">Check available</button>
        <button onclick="window.location.href=''">Order history</button>
        <button onclick="window.location.href=''">Current orders</button>
    </div>
    <div class="image">
        <img src="../pics/16558127774-ssst-kampus.jpg" alt="Image Description">
    </div>
</div>
</body>
</html>
