<?php

session_start();

require_once 'functions.php';
require_once 'database.php';
require_once 'auth.php';

$db = new Database();
$conn = $db->getConnection();


$name = isset($_GET['name']) ? $_GET['name'] : '';
$price = isset($_GET['price']) ? $_GET['price'] : '';
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : '';

$products = getProducts($conn, $name, $price, $category_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    addToCart($product_id, $conn);
}


$conn->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Stilovi/styleProdavnica.css">
    <title>Prodavnica</title>

</head>

<body>

    <div class="auth-container">
        <?php if ($auth->jeUlogovan()) : ?>
            <span>Dobrodošao, <?php echo htmlspecialchars($_SESSION['username']); ?> |</span>
            <a href="logout.php" class="logout-btn">Odjavi se</a>
        <?php else : ?>
            <a href="login.php" class="login-btn">Uloguj se</a>
        <?php endif; ?>
    </div>

    <h1>Dostupni proizvodi</h1>

    <h2>Filtriraj proizvode</h2>
    <form method="get" action="index.php" class="filter-form">
        <input type="text" name="name" placeholder="Naziv proizvoda" value="<?php echo isset($_GET['name']) ? ($_GET['name']) : ''; ?>" class="filter-input">
        <input type="number" name="price" placeholder="Cena do" value="<?php echo isset($_GET['price']) ? $_GET['price'] : ''; ?>" class="filter-input">
        <select name="category_id" class="filter-input">
            <option value="">Sve kategorije</option>
            <?php include 'izaberiKategorijuMain.php'; ?>
        </select>
        <button type="submit" class="filter-btn">Filtriraj</button>
        <a href="index.php" class="clear-filters">
            <button type="button" class="clear-btn">Prikaz svih proizvoda</button>
        </a>
    </form>

    <?php foreach ($products as $product): ?>
        <div class="proizvod">
            <h3><?php echo $product['name']; ?></h3>
            <p class="opis"><?php echo $product['description']; ?></p>
            <p>Kategorija: <?php echo $product['category_name']; ?></p>
            <p>Cena: <?php echo $product['price']; ?> $</p>

            <form method="post" action="index.php">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <button type="submit" name="add_to_cart">Dodaj u korpu</button>
            </form>
        </div>
    <?php endforeach; ?>

    <div class="view-cart-btn-container">
        <a href="cart.php" class="view-cart-btn">Pogledaj korpu</a>
    </div>

</body>

</html>