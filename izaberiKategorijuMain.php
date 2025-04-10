<?php
require_once 'database.php';

$db = new Database();
$conn = $db->getConnection();

$result = $conn->query("SELECT * FROM categories");

while ($row = $result->fetch_assoc()) {
    echo "<option value='{$row['id']}'>{$row['name']}</option>";
}
