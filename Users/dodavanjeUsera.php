<?php
$password = 'admin123';
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$sql = "INSERT INTO users (username, password, role) VALUES ('admin', '$hashed_password','admin')";

$username = 'user2';
$password = 'user234';

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (username, password, role) VALUES ('$username','$hashed_password','user')";

$db = new Database();
$conn = $db->getConnection();
$conn->query($sql);

$conn->close();
