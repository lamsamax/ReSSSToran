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
    <link rel="stylesheet" href="../CSS/gallery.css">
</head>
<body>

<div class="slideshow-container">

    <div class="mySlides fade">
        <div class="numbertext">1 / 3</div>
        <img src="../pics/img1.jpg" style="width:70%">
    </div>

    <div class="mySlides fade">
        <div class="numbertext">2 / 3</div>
        <img src="../pics/img2.jpg" style="width:70%">
    </div>

    <div class="mySlides fade">
        <div class="numbertext">3 / 3</div>
        <img src="../pics/img3.jpg" style="width:70%">
    </div>

    <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
    <a class="next" onclick="plusSlides(1)">&#10095;</a>

</div>
<div id="grad">
    <div class="sidebar">
        <div class="header">
            <h1>ReSSSToran</h1>
            <h2>Gallery</h2>
        </div>
        <div class="sidebar-content">
            <p>
                Our restaurant can comfortably seat 100 guests and features a beautiful terrace. Enjoy dining in our elegant indoor area or relax on our stunning terrace with picturesque views. Perfect for special occasions or a delightful meal, our facilities ensure an unforgettable experience.
            </p>
        </div>
    </div>

    <div class="buttons">
        <button onclick="window.location.href='index.php'">Home</button>
        <button onclick="window.location.href='../../project-2024-group-4/php/menu.php'">Menu</button>
        <button onclick="window.location.href='gallery.php'">Gallery</button>
        <button onclick="window.location.href='contact.php'">Contact</button>
        <button onclick="window.location.href='profile.php'">Profile</button>
    </div>

</div>
</body>
</html>
<script>
    let slideIndex = 1;
    showSlides(slideIndex);

    function plusSlides(n) {
        showSlides(slideIndex += n);
    }

    function currentSlide(n) {
        showSlides(slideIndex = n);
    }

    function showSlides(n) {
        let i;
        let slides = document.getElementsByClassName("mySlides");
        let dots = document.getElementsByClassName("dot");
        if (n > slides.length) {slideIndex = 1}
        if (n < 1) {slideIndex = slides.length}
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        for (i = 0; i < dots.length; i++) {
            dots[i].className = dots[i].className.replace(" active", "");
        }
        slides[slideIndex-1].style.display = "block";
        dots[slideIndex-1].className += " active";
    }
</script>
