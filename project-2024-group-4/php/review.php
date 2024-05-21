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