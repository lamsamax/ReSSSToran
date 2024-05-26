<?php
global $dbc;

include '../../authorization.php';
checkUserRole('customer');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReSSSToran</title>
    <link rel="stylesheet" href="../CSS/contact.css">
    <link href="https://fonts.googleapis.com/css2?family=Amatic+SC:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
<div id="grad">
    <div class="sidebar">
        <div class="header">
            <h1>ReSSSToran</h1>
            <h2>Contact</h2>
        </div>
        <div class="sidebar-content">
            <p>
                Feel free to reach out to us via telephone or email:<br>
                <strong>Tel:</strong> +387 33 975 002<br>
                <strong>Fax:</strong> +387 33 975 030<br>
                <strong>Email:</strong> resturant@ssst.edu.ba
            </p>
        </div>
    </div>

    <div class="buttons">
        <button onclick="window.location.href='index.html'">Home</button>
        <button onclick="window.location.href='../../project-2024-group-4/php/menu.php'">Menu</button>
        <button onclick="window.location.href='gallery.html'">Gallery</button>
        <button onclick="window.location.href='contact.php'">Contact</button>
        <button onclick="window.location.href='profile.php'">Profile</button>
    </div>


    <div class="wrapper" id="contactFormWrapper">
        <form class="form" action="contact.php" method="post">
            <div class="pageTitle title">Contact us</div>
            <div class="secondaryTitle title">Please fill in all data in order to get a response.</div>
            <input type="text" name="name" class="name formEntry" placeholder="Name" required />
            <input type="email" name="email" class="email formEntry" placeholder="Email" required />
            <textarea name="message" class="message formEntry" placeholder="Message" required></textarea>
            <button type="submit" class="submit formEntry">Submit</button>
        </form>
        <?php if (isset($msg)): ?>
            <p><?= htmlspecialchars($msg) ?></p>
        <?php endif; ?>
    </div>
</div>

<script>
    function toggleContactForm() {
        var formWrapper = document.getElementById('contactFormWrapper');
        if (formWrapper.style.display === "none" || formWrapper.style.display === "") {
            formWrapper.style.display = "block";
        } else {
            formWrapper.style.display = "none";
        }
    }
</script>
</body>
</html>
