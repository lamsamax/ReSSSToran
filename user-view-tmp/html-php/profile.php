<?php
// Start session
global $dbc;
session_start();
include 'proba.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$sql = "SELECT mail FROM customer WHERE id = ?";
$stmt = $dbc->prepare($sql);
if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $email = $user['email'];
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
    <title>Edit Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Amatic+SC:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/style.css">
</head>
<body>
<div id="grad">
    <div class="sidebar">
        <div class="header">
            <h1>ReSSSToran</h1>
            <h2>My Profile</h2>
        </div>
        <form class="profile-form" action="update_profile.php" method="post">
            <div class="form-field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($mail); ?>" required>
            </div>
        </form>
        <button type="button" id="changePasswordBtn" class="edit-btn">Change Password</button>
    </div>
    <div class="buttons">
        <button onclick="window.location.href='index.html'">Home</button>
        <button onclick="window.location.href='menu.html'">Menu</button>
        <button onclick="window.location.href='gallery.html'">Gallery</button>
        <button onclick="window.location.href='contact.html'">Contact</button>
        <button onclick="window.location.href='profile.html'">Profile</button>
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
    <div class="wrapper">
        <div class="form">
            <button type="button" class="submit formEntry" onclick="location.href='history1.php';">Active orders</button>
            <button type="button" class="submit formEntry" onclick="location.href='history2.php';">Previous orders</button>
            <button type="button" class="submit formEntry" onclick="location.href='history3.php';">My reviews</button>
        </div>
    </div>
</div>

<script>
    // Get the modal
    var modal = document.getElementById("changePasswordModal");

    // Get the button that opens the modal
    var btn = document.getElementById("changePasswordBtn");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks the button, open the modal
    btn.onclick = function() {
        modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>
</body>
</html>
