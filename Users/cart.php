<?php

session_start();
require_once '../database.php';

$db = new Database();
$conn = $db->getConnection();

$productsInCart = [];

if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_map('intval', $_SESSION['cart']));
    $sql = "SELECT * FROM products WHERE id IN ($ids)";
    $result = $conn->query($sql);
    $productsInCart = $result->fetch_all(MYSQLI_ASSOC);
}

if (isset($_POST['remove_item']) && isset($_POST['remove_id'])) {
    $removeId = $_POST['remove_id'];

    if (isset($_SESSION['cart'])) {

        foreach ($_SESSION['cart'] as $index => $item) {
            if ($item == $removeId) {

                unset($_SESSION['cart'][$index]);
            }
        }

        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }

    header("Location:cart.php");
    exit;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Stilovi/styleKorpa.css">
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
                    <?php echo $product['price']; ?> $

                    <form method="post" action="cart.php" style="display:inline;">
                        <input type="hidden" name="remove_id" value="<?php echo $product['id']; ?>">
                        <button type="submit" name="remove_item">Ukloni</button>
                    </form>

                </li>
            <?php endforeach; ?>
        </ul>
        <a href="user.php">Nazad na prodavnicu</a>
    <?php else: ?>
        <p>Korpa je prazna</p>
        <a href="user.php">Idi u prodavnicu</a>
    <?php endif; ?>
</body>

</html>