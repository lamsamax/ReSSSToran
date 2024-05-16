<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $message = htmlspecialchars($_POST["message"]);

    $to = "imamoviceva@gmail.com";
    $subject = "Message from $name";
    $body = "Name: $name\nEmail: $email\n\nMessage:\n$message";
    $headers = "From: $email\r\n";

    if (mail($to, $subject, $body, $headers)) {
        $msg = "Message sent successfully!";
    } else {
        $msg = "Failed to send message.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<link href="https://fonts.googleapis.com/css2?family=Amatic+SC:wght@400;700&display=swap" rel="stylesheet">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReSSSToran</title>
    <link rel="stylesheet" href="style.css">
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
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam suscipit, mauris at suscipit fermentum, urna quam fermentum purus, a tempus urna lacus sed nibh. Nullam mattis, arcu sed interdum dictum, felis nunc auctor dolor, sit amet pharetra enim magna a purus. Ut consequat id neque vel posuere. Cras efficitur tristique ipsum, sit amet posuere ipsum posuere sit amet. Nulla ut massa sed...
            </p>
        </div>
    </div>

    <div class="buttons">
        <button onclick="window.location.href='index.html'">Home</button>
        <button onclick="window.location.href='menu.html'">Menu</button>
        <button onclick="window.location.href='gallery.html'">Gallery</button>
        <button onclick="window.location.href='contact.html'">Contact</button>
        <button onclick="window.location.href='profile.html'">Profile</button>
    </div>
    <div class="wrapper">
        <form class="form" action="contact.php" method="post">
            <div class="pageTitle title">Contact us</div>
            <div class="secondaryTitle title">Please fill in all data in order to get a response.</div>
            <input type="text" name="name" class="name formEntry" placeholder="Name" required />
            <input type="email" name="email" class="email formEntry" placeholder="Email" required />
            <textarea name="message" class="message formEntry" placeholder="Message" required></textarea>
            <button type="submit" class="submit formEntry">Submit</button>
        </form>
        <?php
        if (isset($msg)) {
            echo "<p>$msg</p>";
        }
        ?>
    </div>
</div>
</body>
</html>
