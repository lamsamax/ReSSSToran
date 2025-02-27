<?php
global $dbc;

include '../../authorization.php';
checkUserRole('customer');

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

        // Format the date of birth to European format
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
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Amatic+SC:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/profile.css">
</head>
<body>
<div id="grad">
    <div class="sidebar">
        <div class="header">
            <h1>ReSSSToran</h1>
            <h2>My Profile</h2>
        </div>
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
                <input type="date" id="dob" name="dob" value="<?php echo (new DateTime($dob))->format('d-m-Y'); ?>" required>
            </div>
            <button type="submit" class="edit-btn">Save Changes</button>
            <button type="button" id="cancelEditBtn" class="edit-btn">Cancel</button>
        </form>
        <div class="action-buttons">
            <button type="button" class="action-btn" onclick="location.href='history1.php';">Active Orders</button>
            <button type="button" class="action-btn" onclick="location.href='../../staff-view/order_history.php';">Previous Orders</button>
            <button type="button" class="action-btn" onclick="location.href='../../staff-view/reviewhistory.php';">My Reviews</button>
        </div>
    </div>
    <div class="buttons">
        <button onclick="window.location.href='index.php'">Home</button>
        <button onclick="window.location.href='../../project-2024-group-4/php/menu.php'">Menu</button>
        <button onclick="window.location.href='gallery.php'">Gallery</button>
        <button onclick="window.location.href='contact.php'">Contact</button>
        <button onclick="window.location.href='profile.php'">Profile</button>
    </div>
    <div id="changePasswordModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <form action="change_password.php" method="post">
                <label for="current_password">Current Password:</label>
                <input type="password" id="current_password" name="current_password" required>
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" required>
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
                <button type="submit">Save Changes</button>
            </form>
        </div>
    </div>
</div>


<script>
    const profileInfo = document.getElementById("profile-info");
    const profileForm = document.getElementById("profile-form");
    const editProfileBtn = document.getElementById("editProfileBtn");
    const cancelEditBtn = document.getElementById("cancelEditBtn");
    const modal = document.getElementById("changePasswordModal");
    const btn = document.getElementById("changePasswordBtn");
    const span = document.getElementsByClassName("close")[0];

    // When the user clicks the edit profile button, show the form and hide the info
    editProfileBtn.onclick = function() {
        profileInfo.style.display = "none";
        profileForm.style.display = "block";
    }

    // When the user clicks the cancel button, hide the form and show the info
    cancelEditBtn.onclick = function() {
        profileForm.style.display = "none";
        profileInfo.style.display = "block";
    }

    // When the user clicks the change password button, open the modal
    btn.onclick = function() {
        modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    }
</script>
</body>
</html>