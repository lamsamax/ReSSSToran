<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include('../mysqli_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    global $dbc;

    $customerStmt = $dbc->prepare("SELECT * FROM CUSTOMER WHERE mail=? AND password=?");
    $customerStmt->bind_param("ss", $email, $password);
    $customerStmt->execute();
    $customerResult = $customerStmt->get_result();

    if ($customerResult->num_rows == 1) {
        $customer = $customerResult->fetch_assoc();
        $_SESSION['user_id'] = $customer['customerID'];
        $_SESSION['user_role'] = ($customer['isAdmin'] == 1) ? 'admin' : 'customer';

        if ($customer['isAdmin'] == 1) {
            header('Location: ../../user-view-tmp/html-php/homeadmin.php');
        } else {
            header('Location: ../../user-view-tmp/html-php/index.php');
        }
        exit();
    } else {
        // Prepared statement for STAFF table
        $staffStmt = $dbc->prepare("SELECT * FROM STAFF WHERE mail=? AND password=?");
        $staffStmt->bind_param("ss", $email, $password);
        $staffStmt->execute();
        $staffResult = $staffStmt->get_result();

        if ($staffResult->num_rows == 1) {
            $staff = $staffResult->fetch_assoc();
            $_SESSION['user_id'] = $staff['staffID'];
            $_SESSION['user_role'] = 'staff';

            header('Location: ../../user-view-tmp/html-php/hometruba.php');
            exit();
        } else {
            $error_message = "Invalid email or password";
        }
    }
    $customerStmt->close();
    $staffStmt->close();
    mysqli_close($dbc);
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link href='https://fonts.googleapis.com/css?family=Titillium+Web:400,300,600' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="../css/loginstyle.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css" integrity="sha384-vp86vTRFVJgpjF9jiIGPEEqYqlDwgyBgEF109VFjmqGmIY/Y4HV4d3Gp2irVfcrp" crossorigin="anonymous">
</head>
<body>
<div class="background">
    <div class="container">
        <div class="login-page">
            <div class="form">
                <?php if (isset($error_message)): ?>
                    <p style="color: red;"><?php echo $error_message; ?></p>
                <?php endif; ?>
                <form method="post" action="login.php">
                    <label for="email">EMAIL</label>
                    <input type="text" id="email" name="email" placeholder="EMAIL" required/>
                    <label for="password">PASSWORD</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" placeholder="PASSWORD" required/>
                        <i class="fas fa-eye" onclick="show()"></i>
                    </div>
                    <div class="button-container">
                        <button type="submit">LOGIN</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function show() {
        var password = document.getElementById("password");
        var icon = document.querySelector(".fas");
        if (password.type === "password") {
            password.type = "text";
        } else {
            password.type = "password";
        }
    }
</script>
</body>
</html>


