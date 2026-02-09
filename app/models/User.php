<?php

class User {
    private $id;
    private $nom;
    private $email;
    private $passwordHash;
    private $role;
    private $dateInscription;

    public function __construct($id, $nom, $email, $passwordHash, $role = 'user', $dateInscription = null) {
        $this->id = $id;
        $this->nom = $nom;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->role = $role;
        $this->dateInscription = $dateInscription ?? date('Y-m-d H:i:s');
    }

    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
    public function getEmail() { return $this->email; }
    public function getPasswordHash() { return $this->passwordHash; }
    public function getRole() { return $this->role; }
    public function getDateInscription() { return $this->dateInscription; }
    
}

class UserDAO {

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
