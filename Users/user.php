<?php
session_start();
require_once '../database.php';

$db = new Database();
$conn = $db->getConnection();


if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (!in_array($product_id, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $product_id;
    }
}

$name = $_GET['name'] ?? '';
$price = $_GET['price'] ?? '';
$category_id = $_GET['category_id'] ?? '';

$sql = "SELECT p.*, c.name as category_name FROM products p
        JOIN categories c ON p.category_id = c.id
        WHERE 1=1";

$params = [];
$types = '';

if (!empty($name)) {
    $sql .= " AND p.name LIKE ?";
    $params[] = "%$name%";
    $types .= "s";
}

if (!empty($price)) {
    $sql .= " AND p.price <= ?";
    $params[] = $price;
    $types .= "d";
}

if (!empty($category_id)) {
    $sql .= " AND p.category_id = ?";
    $params[] = $category_id;
    $types .= "i";
}

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Stilovi/styleProdavnica.css">
    <title>Prodavnica</title>

</head>

<body>

    <h1>Dostupni proizvodi</h1>

    <h2>Filtriraj proizvode</h2>
    <form method="get" action="user.php" class="filter-form">
        <input type="text" name="name" placeholder="Naziv proizvoda" value="<?php echo isset($_GET['name']) ? htmlspecialchars($_GET['name']) : ''; ?>" class="filter-input">
        <input type="number" name="price" placeholder="Cena do" value="<?php echo isset($_GET['price']) ? $_GET['price'] : ''; ?>" class="filter-input">
        <select name="category_id" class="filter-input">
            <option value="">Sve kategorije</option>
            <?php include 'AdminContent/izaberiKategoriju.php'; ?>
        </select>
        <button type="submit" class="filter-btn">Filtriraj</button>
        <a href="user.php" class="clear-filters">
            <button type="button" class="clear-btn">Prikaz svih proizvoda</button>
        </a>
    </form>

    <?php foreach ($products as $product): ?>
        <div class="proizvod">
            <h3><?php echo $product['name']; ?></h3>
            <p class="opis"><?php echo $product['description']; ?></p>
            <p>Kategorija: <?php echo $product['category_name']; ?></p>
            <p>Cena: <?php echo $product['price']; ?> $</p>

            <form method="post" action="user.php">
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