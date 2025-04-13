<?php

class Autorizacija
{
    private $conn;

    public function __construct()
    {
        require_once 'database.php';
        $db = new Database();
        $this->conn = $db->getConnection();


        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function pokreniSesiju()
    {
        if (!isset($_SESSION['role'])) {
            $_SESSION['role'] = 'guest';
            $_SESSION['username'] = 'Gost';
        }
    }

    public function jeAdmin()
    {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }

    public function jeUlogovan()
    {
        return isset($_SESSION['user_id']);
    }

    public function jeGost()
    {
        return $_SESSION['role'] === 'guest';
    }

    public function login($username, $password)
    {
        $sql = "SELECT * FROM users WHERE username=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $rezultat = $stmt->get_result();
        $user = $rezultat->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            return true;
        }

        return false;
    }

    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();
    }
}

$auth = new Autorizacija();
$auth->pokreniSesiju();
