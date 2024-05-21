<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include('../mysqli_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check in CUSTOMER table
    global $dbc;
    $customerQuery = "SELECT * FROM CUSTOMER WHERE mail='$email' AND password='$password'";
    $customerResult = mysqli_query($dbc, $customerQuery);

    if (mysqli_num_rows($customerResult) == 1) {
        $customer = mysqli_fetch_assoc($customerResult);
        $_SESSION['user_id'] = $customer['userID'];
        $_SESSION['user_role'] = ($customer['isAdmin'] == 1) ? 'admin' : 'customer';

        if ($customer['isAdmin'] == 1) {
            header('Location: adminhome.php');
        } else {
            header('Location: customerhome.php');
        }
        exit();
    } else {
        // Check in STAFF table
        $staffQuery = "SELECT * FROM STAFF WHERE mail='$email' AND password='$password'";
        $staffResult = mysqli_query($dbc, $staffQuery);

        if (mysqli_num_rows($staffResult) == 1) {
            $staff = mysqli_fetch_assoc($staffResult);
            $_SESSION['user_id'] = $staff['staffID'];
            $_SESSION['user_role'] = 'staff';

            header('Location: staffhome.php');
            exit();
        } else {
            $error_message = "Invalid email or password";
        }
    }
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


