<?php


$dbc = mysqli_connect(
    "152.67.94.35:3306",
    "root",
    "Password123/",
    "project"
);

if (!$dbc) {
    die("Connection failed: " . mysqli_connect_error());
}
