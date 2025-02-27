<?php
global $dbc;

include '../../authorization.php';
checkUserRole('customer');
?>

<!DOCTYPE html>
<html lang="en">
<link href="https://fonts.googleapis.com/css2?family=Amatic+SC:wght@400;700&display=swap" rel="stylesheet">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReSSSToran</title>
    <link rel="stylesheet" href="../CSS/index.css">
</head>
<body>
<div id="grad">
    <div class="sidebar">
        <div class="header">
                <h1>ReSSSToran</h1>
        </div>
        <div class="sidebar-content">
            <p>
                Located in the heart of the university, ReSSSToran is a vibrant spot for students and faculty to enjoy diverse, locally-sourced meals. It's not just a place for quick bites between classes but also a social hub for lively discussions. With its friendly service and affordable prices, the bistro is a cherished part of campus life.            </p>
        </div>
    </div>

    <div class="buttons">
        <button onclick="window.location.href='index.php'">Home</button>
        <button onclick="window.location.href='../../project-2024-group-4/php/menu.php'">Menu</button>
        <button onclick="window.location.href='gallery.php'">Gallery</button>
        <button onclick="window.location.href='contact.php'">Contact</button>
        <button onclick="window.location.href='profile.php'">Profile</button>
    </div>
    <div class="image">
        <img src="../pics/16558127774-ssst-kampus.jpg" alt="Image Description">
    </div>
</div>
</body>
</html>
