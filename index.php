<?php

require_once 'database.php';

/*$password = 'admin123';
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$sql = "INSERT INTO users (username, password, role) VALUES ('admin', '$hashed_password','admin')";
*/

/*
$username = 'user2';
$password = 'user234';

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (username, password, role) VALUES ('$username','$hashed_password','user')";

$db = new Database();
$conn = $db->getConnection();
$conn->query($sql);

$conn->close();
*/

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="Stilovi/style.css">
    <title>Login Page</title>
</head>

<body>
    <form method="POST">
        <label for="username">Korisničko ime:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="password">Lozinka:</label>
        <input type="password" id="password" name="password" required><br>

        <button type="submit">Prijavi se</button>
    </form>
</body>

</html>