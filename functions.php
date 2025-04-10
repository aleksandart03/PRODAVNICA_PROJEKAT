<?php

function addToCart($product_id)
{
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (!in_array($product_id, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $product_id;
    }
}

function getProducts($conn, $name, $price, $category_id)
{
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

    return $products;
}


function getProductsInCart($conn)
{
    $productsInCart = [];

    if (!empty($_SESSION['cart'])) {
        $ids = implode(',', array_map('intval', $_SESSION['cart']));
        $sql = "SELECT * FROM products WHERE id IN ($ids)";
        $result = $conn->query($sql);
        $productsInCart = $result->fetch_all(MYSQLI_ASSOC);
    }

    return $productsInCart;
}


function removeItemFromCart($removeId)
{
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $index => $item) {
            if ($item == $removeId) {
                unset($_SESSION['cart'][$index]);
            }
        }

        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}
