<?php

session_start();
require_once 'database.php';

$greska = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $db = new Database();
    $conn = $db->getConnection();

    $sql = "SELECT * FROM users WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $rezultat = $stmt->get_result();
    $user = $rezultat->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'admin') {
            header("Location: Users/admin.php");
        } else {
            header("Location: Users/user.php");
        }
        exit();
    } else {
        $greska = "Pogrešno korisničko ime ili lozinka!";
    }

    $stmt->close();
    $conn->close();
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
    </form>
</body>

</html>