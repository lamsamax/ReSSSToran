<?php
include ('../../authorization.php');
checkUserRole('customer');

global $dbc;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $grade = $_POST['grade'];
    $description = $_POST['description'];
    $customerID = $_SESSION['user_id'];

    // Update the grade and review for the latest order of the user
    $sql = "UPDATE ORDERS SET grade = ?, review = ? WHERE customer = ? AND orderID = ?";
    $stmt = $dbc->prepare($sql);
    if (!$stmt) {
        die("Error preparing statement: " . $dbc->error);
    }

    $stmt->bind_param("isii", $grade, $description, $customerID, $_SESSION['current_order_id']);
    $stmt->execute();
    $stmt->close();

    echo "Review submitted successfully!";
    // Optionally, redirect to another page
    header('Location: ../../user-view-tmp/html-php/index.php');
    exit();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Review</title>
    <link rel="stylesheet" type="text/css" href="../css/reviewstyle.css">
</head>
<body>
<div class="container">
    <h1>Write Your Review!</h1>
    <form action="#" method="POST">
        <label for="grade">Grade (1-5):</label>
        <select id="grade" name="grade">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select>

        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="4" placeholder="Write a description..."></textarea>

        <button type="submit">Submit Review</button>
    </form>
</div>
</body>
</html>