<?php
require_once __DIR__ . '/../models/User.php';

class UserRepository {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /* -------------------------
       HASH PASSWORD
    --------------------------*/
    public function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /* -------------------------
       CREATE USER
    --------------------------*/
    public function createUser($nom, $email, $password, $role = 'user') {

        $passwordHash = $this->hashPassword($password);

        $sql = "INSERT INTO takalo_users (nom, email, password, role)
                VALUES (:nom, :email, :password, :role)";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nom' => $nom,
            ':email' => $email,
            ':password' => $passwordHash,
            ':role' => $role
        ]);
    }

    /* -------------------------
       GET USER BY EMAIL
    --------------------------*/
    public function getUserByEmail($email) {

        $sql = "SELECT * FROM takalo_users WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':email' => $email]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return new User(
                $data['id'],
                $data['nom'],
                $data['email'],
                $data['password'],
                $data['role'],
                $data['date_inscription']
            );
        }

        return null;
    }

    /* -------------------------
       GET USER BY ID
    --------------------------*/
    public function getUserById($id) {

        $sql = "SELECT * FROM takalo_users WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return new User(
                $data['id'],
                $data['nom'],
                $data['email'],
                $data['password'],
                $data['role'],
                $data['date_inscription']
            );
        }

        return null;
    }

    /* -------------------------
       COUNT USERS (STAT)
    --------------------------*/
    public function countUsers() {

        $sql = "SELECT COUNT(*) as total FROM takalo_users";
        $stmt = $this->pdo->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['total'];
    }

    /* -------------------------
       VERIFICATION LOGIN
    --------------------------*/
    public function verifyLogin($email, $password) {

        $user = $this->getUserByEmail($email);

        if ($user && password_verify($password, $user->getPasswordHash())) {
            return $user; // login réussi
        }

        return null; // login échoué
    }
}
