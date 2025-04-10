<?php

require_once 'auth.php';

$greska = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];


    $autorizacija = new Autorizacija();


    if ($autorizacija->login($username, $password)) {

        if (Autorizacija::jeAdmin()) {
            header("Location: Users/admin.php");
        } else {
            header("Location: index.php");
        }
        exit();
    } else {
        $greska = "Pogrešno korisničko ime ili lozinka!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="Stilovi/styleLogin.css?v=<?php echo time(); ?>">
</head>

<body>
    <form method="POST" action="login.php">
        <label for="username">Korisničko ime:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="password">Lozinka:</label>
        <input type="password" id="password" name="password" required><br>

        <button type="submit">Prijavi se</button>

        <?php if (!empty($greska)) : ?>
            <div class="error-message"><?php echo $greska; ?></div>
        <?php endif; ?>


        <a href="index.php" class="nazad-dugme">Nazad na prodavnicu</a>
    </form>


</body>

</html>