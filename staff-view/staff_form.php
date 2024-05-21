<?php
include ('db.php');
global $dbc;

$id = '';
$name = '';
$surname = '';
$dob = '';
$mail = '';
$password = '';

if(isset($_GET['staffID'])){
    $id = $_GET['staffID'];
    $sql = "SELECT * FROM STAFF WHERE staffID = ?";
    $stmt = $dbc->prepare($stmt);
}


?>
