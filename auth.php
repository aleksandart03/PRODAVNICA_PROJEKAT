<?php
session_start();

class Autorizacija
{
    public $conn;


    public function __construct()
    {
        require_once 'database.php';
        $db = new Database();
        $this->conn = $db->getConnection();
    }


    public static function pokreniSesiju()
    {
        if (!isset($_SESSION['role'])) {
            $_SESSION['role'] = 'guest';
            $_SESSION['username'] = 'Gost';
        }
    }


    public static function jeAdmin()
    {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }


    public static function jeUlogovan()
    {
        return isset($_SESSION['user_id']);
    }


    public static function jeGost()
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

    public static function logout()
    {
        session_start();
        session_unset();
        session_destroy();
    }
}

Autorizacija::pokreniSesiju();
