<?php
session_start();
include('../mysqli_connect.php');

function checkUserRole($requiredRole) {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != $requiredRole) {
        header('Location: ../project-2024-group-4/login.php');
        exit();
    }
}