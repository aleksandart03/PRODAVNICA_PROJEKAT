<?php

session_start();

require_once 'database.php';
require_once 'functions.php';

$db = new Database();
$conn = $db->getConnection();


$productsInCart = getProductsInCart($conn);


if (isset($_POST['remove_item']) && isset($_POST['remove_id'])) {
    $removeId = $_POST['remove_id'];
    removeItemFromCart($removeId, $conn);

    header("Location: cart.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Stilovi/styleKorpa.css">
    <title>Korpa</title>
</head>

<body>
    <h1>Korpa</h1>

    <?php if (!empty($productsInCart)): ?>
        <ul>
            <?php foreach ($productsInCart as $product): ?>
                <li>
                    <strong><?php echo $product['name']; ?></strong> -
                    <em><?php echo htmlspecialchars($product['description']); ?></em><br>
                    Cena: <?php echo $product['price']; ?> $<br>

                    Količina:
                    <?php
                    if (isset($product['quantity'])) {
                        echo $product['quantity'];
                    } elseif (isset($_SESSION['cart'][$product['id']])) {
                        echo $_SESSION['cart'][$product['id']];
                    } else {
                        echo '1';
                    }
                    ?>

                    <form method="post" action="cart.php" style="display:inline;">
                        <input type="hidden" name="remove_id" value="<?php echo $product['id']; ?>">
                        <button type="submit" name="remove_item">Ukloni</button>
                    </form>
                </li>

            <?php endforeach; ?>
        </ul>
        <a href="index.php">Nazad na prodavnicu</a>
    <?php else: ?>
        <p>Korpa je prazna</p>
        <a href="index.php">Idi u prodavnicu</a>
    <?php endif; ?>
</body>

</html>