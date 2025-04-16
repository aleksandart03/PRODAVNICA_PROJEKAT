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

if (isset($_POST['change_quantity_id']) && isset($_POST['action'])) {
    $productId = $_POST['change_quantity_id'];
    $action = $_POST['action'];

    changeQuantity($conn, $productId, $action);

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
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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
                        <input type="hidden" name="change_quantity_id" value="<?php echo $product['id']; ?>">
                        <input type="hidden" name="action" value="increase">
                        <button class="plus" type="submit">+</button>
                    </form>


                    <form method="post" action="cart.php" style="display:inline;">
                        <input type="hidden" name="change_quantity_id" value="<?php echo $product['id']; ?>">
                        <input type="hidden" name="action" value="decrease">
                        <button class="minus" type="submit">-</button>
                    </form>

                    <form method="post" action="cart.php" style="display:inline;">
                        <input type="hidden" name="remove_id" value="<?php echo $product['id']; ?>">
                        <button type="submit" name="remove_item">Ukloni</button>
                    </form>
                </li>

            <?php endforeach; ?>
        </ul>
        <?php
        $totalPrice = 0;
        foreach ($productsInCart as $product) {
            $product_id = $product['id'];


            if (!isset($product['quantity']) && isset($_SESSION['cart'][$product_id])) {
                $quantity = $_SESSION['cart'][$product_id];
            } else {

                $quantity = $product['quantity'];
            }

            $subtotal = $product['price'] * $quantity;
            $totalPrice += $subtotal;
        }
        ?>

        <p><strong>Ukupna cena: </strong><?php echo number_format($totalPrice, 2); ?> $</p>

        <p class="txt-korpa">Nazad na prodavnicu</p>
        <a class="povratak" href="index.php"> <i class='bx bx-left-arrow-alt icon'></i></a>
    <?php else: ?>
        <p>Korpa je prazna</p>
        <p class="txt-prazna">Nazad u prodavnicu</p>
        <a class="povratak" href="index.php"> <i class='bx bx-left-arrow-alt icon'></i> </a>
    <?php endif; ?>
</body>

</html>