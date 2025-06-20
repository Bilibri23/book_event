<?php
require_once __DIR__ . '/Database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function findByEmail($email) {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        return $this->db->single();
    }

    public function register($data) {
        $this->db->query('INSERT INTO users (username, email, password_hash, role) VALUES (:username, :email, :password_hash, :role)');
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password_hash', password_hash($data['password'], PASSWORD_DEFAULT));
        $this->db->bind(':role', 'user'); // All new signups are 'user'
        return $this->db->execute();
    }

    public function login($email, $password) {
        $user = $this->findByEmail($email);
        if ($user && password_verify($password, $user->password_hash)) {
            return $user; // Success, return user object
        }
        return false; // Failed
    }
}