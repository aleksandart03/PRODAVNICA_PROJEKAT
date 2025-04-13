<?php

function addToCart($product_id, $conn)
{
    require_once 'auth.php';

    $auth = new Autorizacija();

    if ($auth->jeUlogovan()) {
        $user_id = $_SESSION['user_id'];

        $sql = "SELECT id FROM cart WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $cart = $result->fetch_assoc();

        if (!$cart) {
            $sql = "INSERT INTO cart (user_id) VALUES (?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $cart_id = $stmt->insert_id;
        } else {
            $cart_id = $cart['id'];
        }

        $sql = "SELECT quantity FROM cart_product WHERE cart_id = ? AND product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $cart_id, $product_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $cartProduct = $result->fetch_assoc();
            $new_quantity = $cartProduct['quantity'] + 1;

            $update_sql = "UPDATE cart_product SET quantity = ? WHERE cart_id = ? AND product_id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("iii", $new_quantity, $cart_id, $product_id);
            $update_stmt->execute();
        } else {

            $insert_sql = "INSERT INTO cart_product (cart_id, product_id, quantity) VALUES (?, ?, 1)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("ii", $cart_id, $product_id);
            $insert_stmt->execute();
        }
    } else {

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }


        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += 1;
        } else {

            $_SESSION['cart'][$product_id] = 1;
        }
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

    require_once 'auth.php';

    $auth = new Autorizacija();

    if ($auth->jeUlogovan()) {
        $user_id = $_SESSION['user_id'];
        $sql = "SELECT p.*, cp.quantity FROM cart_product cp
                JOIN products p ON cp.product_id = p.id
                JOIN cart c ON cp.cart_id = c.id
                WHERE c.user_id = ? AND c.created_at > NOW() - INTERVAL 1 DAY";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $productsInCart = $result->fetch_all(MYSQLI_ASSOC);
    } else {

        if (!empty($_SESSION['cart'])) {
            $ids = implode(',', array_keys($_SESSION['cart']));;
            $sql = "SELECT * FROM products WHERE id IN ($ids)";
            $result = $conn->query($sql);
            $productsInCart = $result->fetch_all(MYSQLI_ASSOC);
        }
    }

    return $productsInCart;
}


function removeItemFromCart($removeId, $conn)
{
    require_once 'auth.php';

    $auth = new Autorizacija();

    if ($auth->jeUlogovan()) {
        $user_id = $_SESSION['user_id'];

        $sql = "DELETE FROM cart_product WHERE product_id = ? AND cart_id IN 
                (SELECT id FROM cart WHERE user_id = ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $removeId, $user_id);
        $stmt->execute();
    } else {
        if (isset($_SESSION['cart'][$removeId])) {
            unset($_SESSION['cart'][$removeId]);
        }
    }
}
