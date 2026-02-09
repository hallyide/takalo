<?php
function e($v){ return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8'); }
function cls_invalid($errors, $field){ return ($errors[$field] ?? '') !== '' ? 'is-invalid' : ''; }
?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>

    <!-- CSS Metis -->
    <link rel="icon" type="image/svg+xml" href="/assets/icons/favicon.svg">
    <link rel="stylesheet" crossorigin href="/assets/main-BQhM7myw.css">

    <!-- JS Metis -->
    <script type="module" crossorigin src="/assets/vendor-bootstrap-C9iorZI5.js"></script>
    <script type="module" crossorigin src="/assets/vendor-ui-CflGdlft.js"></script>
</head>

<body class="bg-light">

<div class="container min-vh-100 d-flex align-items-center justify-content-center">
    <div class="col-md-6 col-lg-4">
        <div class="card shadow-sm">

            <div class="card-header text-center bg-white border-0">
                <img src="/assets/images/logo.svg" height="40" class="mb-2">
                <h4 class="fw-bold text-primary mb-0">Metis</h4>
                <p class="text-muted mb-0">Créer un compte</p>
            </div>

            <div class="card-body">

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success">
                        Inscription réussie ✅
                    </div>
                <?php endif; ?>

                <form id="registerForm" method="post" action="/register" novalidate>
                    <div id="formStatus" class="alert d-none"></div>

                    <!-- Nom -->
                    <div class="mb-3">
                        <label class="form-label">Nom</label>
                        <input
                            id="nom"
                            name="nom"
                            class="form-control <?= cls_invalid($errors,'nom') ?>"
                            value="<?= e($values['nom'] ?? '') ?>"
                            placeholder="Votre nom"
                        >
                        <div class="invalid-feedback" id="nomError"><?= e($errors['nom'] ?? '') ?></div>
                    </div>

                    <!-- Prénom -->
                    <div class="mb-3">
                        <label class="form-label">Prénom</label>
                        <input
                            id="prenom"
                            name="prenom"
                            class="form-control <?= cls_invalid($errors,'prenom') ?>"
                            value="<?= e($values['prenom'] ?? '') ?>"
                            placeholder="Votre prénom"
                        >
                        <div class="invalid-feedback" id="prenomError"><?= e($errors['prenom'] ?? '') ?></div>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            class="form-control <?= cls_invalid($errors,'email') ?>"
                            value="<?= e($values['email'] ?? '') ?>"
                            placeholder="Votre email"
                        >
                        <div class="invalid-feedback" id="emailError"><?= e($errors['email'] ?? '') ?></div>
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label class="form-label">Mot de passe</label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            class="form-control <?= cls_invalid($errors,'password') ?>"
                            placeholder="Mot de passe"
                        >
                        <div class="invalid-feedback" id="passwordError"><?= e($errors['password'] ?? '') ?></div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-3">
                        <label class="form-label">Confirmation</label>
                        <input
                            id="confirm_password"
                            name="confirm_password"
                            type="password"
                            class="form-control <?= cls_invalid($errors,'confirm_password') ?>"
                            placeholder="Confirmez le mot de passe"
                        >
                        <div class="invalid-feedback" id="confirmPasswordError"><?= e($errors['confirm_password'] ?? '') ?></div>
                    </div>

                    <button class="btn btn-primary w-100" type="submit">
                        <i class="bi bi-person-plus me-2"></i>
                        S'inscrire
                    </button>
                </form>

                <div class="text-center mt-3">
                    <a href="/login">Se connecter</a>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="js/validation-register-ajax.js" defer></script>
</body>
</html>
