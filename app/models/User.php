<?php

class User {
    private $id;
    private $nom;
    private $prenom;
    private $email;
    private $passwordHash;

    public function __construct($id, $nom, $prenom, $email, $passwordHash) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
    }

    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
    public function getPrenom() { return $this->prenom; }
    public function getEmail() { return $this->email; }
    public function getPasswordHash() { return $this->passwordHash; }
}
