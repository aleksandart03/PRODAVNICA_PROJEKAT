<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['dodaj'])) {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $description = $_POST['description'];
        $category_id = $_POST['category_id'];

        $db = new Database();
        $conn = $db->getConnection();
        $sql = "SELECT * FROM products WHERE name = ? AND description = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $name, $description);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $message = "Proizvod sa ovim  opisom već postoji.";
        } else {
            $sqlInsert = "INSERT INTO products (name, price, description, category_id) VALUES (?, ?, ?, ?)";
            $stmtInsert = $conn->prepare($sqlInsert);
            $stmtInsert->bind_param("sdsi", $name, $price, $description, $category_id);
            if ($stmtInsert->execute()) {
                $message = "Proizvod je uspešno dodat.";
            } else {
                $message = "Greška prilikom dodavanja proizvoda.";
            }
        }

        $stmt->close();
        $conn->close();
    }

    if (isset($_POST['delete'])) {
        $productId = $_POST['product_id'];

        $db = new Database();
        $conn = $db->getConnection();

        $sql = "DELETE FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $productId);

        if ($stmt->execute()) {
            $message = "Proizvod je uspešno obrisan.";
        } else {
            $message = "Došlo je do greške prilikom brisanja proizvoda.";
        }

        $stmt->close();
        $conn->close();
    }

    if (isset($_POST['add_category'])) {
        $category_name = $_POST['category_name'];

        if (!empty($category_name)) {
            $db = new Database();
            $conn = $db->getConnection();

            $sql = "INSERT INTO categories (name) VALUES (?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $category_name);

            if ($stmt->execute()) {
                $message = "Kategorija je uspešno dodata!";
            } else {
                $message = "Greška prilikom dodavanja kategorije.";
            }

            $stmt->close();
            $conn->close();
        } else {
            $message = "Molimo popunite naziv kategorije!";
        }
    }

    $products = [];
    if (isset($_POST['show_products'])) {
        $db = new Database();
        $conn = $db->getConnection();


        $name_filter = isset($_POST['name']) ? $_POST['name'] : '';
        $price_filter = isset($_POST['price']) ? $_POST['price'] : '';
        $category_filter = isset($_POST['category_id']) ? $_POST['category_id'] : '';


        $sql = "SELECT p.id, p.name, p.price, p.description, c.name AS category_name
            FROM products p
            JOIN categories c ON p.category_id = c.id
            WHERE 1=1";  // 


        if (!empty($name_filter)) {
            $sql .= " AND p.name LIKE '%$name_filter%'";
        }

        if (!empty($price_filter)) {
            $sql .= " AND p.price <= $price_filter";
        }

        if (!empty($category_filter)) {
            $sql .= " AND p.category_id = $category_filter";
        }

        $result = $conn->query($sql);


        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        }
        $conn->close();
    }
}
