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
