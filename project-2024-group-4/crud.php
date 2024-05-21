<?php

$conn = mysqli_connect('host', 'jane doe', 'xxx', 'database');

$showResults = false;

if ($_POST){

    $sql = $_POST['sql'];

    $query = mysqli_query($conn, $sql);

    if (!is_bool($query) && mysqli_num_rows($query)>0){
        $showResults = true;
        $fields = mysqli_fetch_fields($query);
    }

    elseif ($query) {
        var_dump(mysqli_affected_rows($conn));
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>reSSSToran</title>
</head>
<style>

body{
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        height: 100%;
    }

    .parent {
    /* background: linear-gradient(to right, white, #315287);*/
    background-color: whitesmoke;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        width: 100%;
    }

    .login-container {
    background: white;
    padding: 25px 40px;
        border-radius: 15px;
        box-shadow: 0 10px 10px rgba(0,0,0,0.1);
        width: 300px;
    }


    h2 {
    color: #333;
    font-weight: 600;
        margin-bottom: 20px;
        text-align: center;

    }

    .input-group {
    margin-bottom: 25px;
    }

    .input-group label {
    display: block;
    margin-bottom: 5px;
    }

    .input-group input {
    width: 100%;
    padding: 8px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .login-button {
    background-color: #02182e;
        color: white;
        border: none;
        padding: 10px;
        width: 100%;
        border-radius: 4px;
        cursor: pointer;
        font-size: 15px;
    }

    .login-button:hover {
    background-color: #315287;

    }

</style>
<body>
<section class="parent">
<div class="login-container">
    <h2>Log In</h2>
    <form action="/submit-your-login-form" method="POST">
        <div class="input-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="input-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="login-button">Log In</button>
    </form>
</div>
</section>

<?php if ($showResults): ?>
    <table>
        <tr>
            <?php foreach ($fields as $field): ?>
                <th><?= $field->name ?></th>
            <?php endforeach; ?>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($query)): ?>
            <tr>
                <?php foreach ($fields as $field): ?>
                    <td><?= $row[$field->name] ?></td>
                <?php endforeach; ?>
            </tr>
        <?php endwhile; ?>
    </table>
<?php endif; ?>

</body>
</html>