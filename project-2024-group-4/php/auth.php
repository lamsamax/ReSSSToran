<?php
session_start();
include('../mysqli_connect.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function checkUserRole($requiredRole) {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != $requiredRole) {
        header('Location: login.php');
        exit();
    }
}


