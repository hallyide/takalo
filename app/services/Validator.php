<?php
class Validator {
public static function validateRegister(array $input, UserRepository $repo = null) {
    $errors = [
        'nom' => '', 'prenom' => '', 'email' => '',
        'password' => '', 'confirm_password' => ''
    ];

    $values = [
        'nom' => trim((string)($input['nom'] ?? '')),
        'prenom' => trim((string)($input['prenom'] ?? '')),
        'email' => trim((string)($input['email'] ?? '')),
    ];

    $password = (string)($input['password'] ?? '');
    $confirm  = (string)($input['confirm_password'] ?? '');

    if (mb_strlen($values['nom']) < 2)
        $errors['nom'] = "Le nom doit contenir au moins 2 caractères.";

    if (mb_strlen($values['prenom']) < 2)
        $errors['prenom'] = "Le prénom doit contenir au moins 2 caractères.";

    if ($values['email'] === '')
        $errors['email'] = "L'email est obligatoire.";
    elseif (!filter_var($values['email'], FILTER_VALIDATE_EMAIL))
        $errors['email'] = "Email invalide.";

    if (strlen($password) < 8)
        $errors['password'] = "Mot de passe minimum 8 caractères.";

    if ($password !== $confirm)
        $errors['confirm_password'] = "Les mots de passe ne correspondent pas.";

    if ($repo && $errors['email'] === '' && $repo->emailExists($values['email']))
        $errors['email'] = "Cet email est déjà utilisé.";

    $ok = true;
    foreach ($errors as $e) {
        if ($e !== '') { $ok = false; break; }
    }

    return ['ok' => $ok, 'errors' => $errors, 'values' => $values];
  }

  public static function validateLogin(array $input, UserRepository $repo = null) {
    $errors = [
        'email' => '',
        'password' => '',
    ];

    $values = [
        'email' => trim((string)($input['email'] ?? '')),
    ];

    $password = (string)($input['password'] ?? '');

    // 1️⃣ Email
    if ($values['email'] === '') {
        $errors['email'] = "L'email est obligatoire.";
    } elseif (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "L'email n'est pas valide.";
    }

    // 2️⃣ Existence de l'utilisateur
    if ($repo && $errors['email'] === '') {
        $user = $repo->findByEmail($values['email']);
        if (!$user) {
            $errors['email'] = "Email ou mot de passe incorrect.";
        }
    }

    // 3️⃣ Vérification du mot de passe
    if ($repo && $errors['email'] === '' && $password !== '') {
        $user = $repo->findByEmail($values['email']);
        if ($user && !password_verify($password, $user->getPasswordHash())) {
            $errors['password'] = "Email ou mot de passe incorrect.";
        }
    }

    // 4️⃣ Mot de passe vide
    if ($password === '') {
        $errors['password'] = "Le mot de passe est obligatoire.";
    }

    // 5️⃣ Résultat final
    $ok = true;
    foreach ($errors as $msg) {
        if ($msg !== '') {
            $ok = false;
            break;
        }
    }

    return [
        'ok' => $ok,
        'errors' => $errors,
        'values' => $values
    ];
  }

}
