<?php

$servername = "152.67.94.35";
$username = "root";
$password = "Password123/";
$dbname = "project";

// Create connection
$dbc = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($dbc->connect_error) {
    die("Connection failed: " . $dbc->connect_error);
}
?>
