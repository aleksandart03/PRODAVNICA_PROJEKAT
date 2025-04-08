<?php
session_start();

require_once 'AdminContent/validacijaAdmina.php';

require_once '../database.php';

require_once 'AdminContent/operacijeAdmin.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Stilovi/styleAdmin.css">
    <title>Admin Page</title>
</head>

<body>
    <h1 class="naslov">Dobrodošao, <?php echo $_SESSION['username']; ?> (admin)</h1>

    <div class="container">


        <form class="forma-dodaj" method="post" action="admin.php">
            <h2>Dodaj novi proizvod</h2>
            <input type="text" name="name" placeholder="Naziv proizvoda" required>
            <input type="number" step="0.01" name="price" placeholder="Cena u $" required>
            <textarea name="description" placeholder="Opis proizvoda"></textarea>

            <select name="category_id" required>

                <option value="">Izaberi kategoriju</option>

                <?php include 'AdminContent/izaberiKategoriju.php'; ?>
            </select>

            <button type="submit" name="dodaj">Dodaj proizvod</button>
        </form>

        <form class="forma-obrisi" method="post" action="admin.php">
            <h2>Obriši proizvod</h2>
            <input type="number" name="product_id" placeholder="Unesite ID proizvoda" required>
            <button type="submit" name="delete">Obriši proizvod</button>
        </form>


        <form class="forma-dodaj-kategoriju" method="post" action="admin.php">
            <h2>Dodaj novu kategoriju</h2>
            <input type="text" name="category_name" placeholder="Naziv kategorije" required>
            <button type="submit" name="add_category">Dodaj kategoriju</button>
        </form>

        <?php if (isset($message)) : ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>

        <form class="pretrazi" method="post" action="admin.php">
            <h3>Pretrazi proizvode (bez unosa prikazace sve dostupno)</h3>
            <input type="text" name="name" placeholder="Naziv proizvoda">
            <input type="number" step="0.01" name="price" placeholder="Cena do">
            <select name="category_id">
                <option value="">Izaberi kategoriju</option>
                <?php include 'AdminContent/izaberiKategoriju.php'; ?>
            </select>
            <button type="submit" name="show_products">Prikazi</button>
        </form>

        <div class="tabela">
            <?php if (isset($_POST['show_products']) && !empty($products)) : ?>
                <h3 class="product-list-title">Lista svih proizvoda:</h3>
                <table class="product-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Naziv</th>
                            <th>Cena</th>
                            <th>Opis</th>
                            <th>Kategorija</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product) : ?>
                            <tr>
                                <td><?php echo $product['id']; ?></td>
                                <td><?php echo $product['name']; ?></td>
                                <td><?php echo $product['price']; ?> $</td>
                                <td><?php echo $product['description']; ?></td>
                                <td><?php echo $product['category_name']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php elseif (isset($_POST['show_products']) && empty($products)) : ?>
                <p>Trenutno nema proizvoda.</p>
            <?php endif; ?>
        </div>
        <form class="forma-odjava" method="post" action="../logout.php">
            <button class="dugme" type="submit">Odjavi se</button>
        </form>

    </div>

</body>

</html>