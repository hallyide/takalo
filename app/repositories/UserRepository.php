<?php
require_once __DIR__ . '/../models/User.php';

class UserRepository {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function emailExists($email) {
        $st = $this->pdo->prepare(
            "SELECT 1 FROM users WHERE email = ? LIMIT 1"
        );
        $st->execute([(string)$email]);
        return (bool)$st->fetchColumn();
    }

    public function create($nom, $prenom, $email, $hash) {
        $st = $this->pdo->prepare("
            INSERT INTO users (nom, prenom, email, password_hash)
            VALUES (?, ?, ?, ?)
        ");
        $st->execute([
            (string)$nom,
            (string)$prenom,
            (string)$email,
            (string)$hash
        ]);

        return $this->pdo->lastInsertId();
    }

    public function findByEmail($email) {
        $st = $this->pdo->prepare(
            "SELECT * FROM users WHERE email = ? LIMIT 1"
        );
        $st->execute([(string)$email]);
        $row = $st->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        return new User(
            $row['id'],
            $row['nom'],
            $row['prenom'],
            $row['email'],
            $row['password_hash']
        );
    }
}
