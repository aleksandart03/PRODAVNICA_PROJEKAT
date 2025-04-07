<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="../Stilovi/styleAdmin.css">
</head>

<body>
    <div class="sve">
        <h1 class="naslov">Dobrodošao, <?php echo $_SESSION['username']; ?> (admin)</h1>


        <form class="forma" method="post" action="../logout.php">
            <button class="dugme" type="submit">Odjavi se</button>
        </form>

    </div>
</body>

</html>