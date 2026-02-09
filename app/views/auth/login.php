<?php
function e($v){ return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8'); }
function cls_invalid($errors, $field){ return ($errors[$field] ?? '') !== '' ? 'is-invalid' : ''; }
?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>

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
                <p class="text-muted mb-0">Connexion à votre compte</p>
            </div>

            <div class="card-body">

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success">
                        Connexion réussie ✅
                    </div>
                <?php endif; ?>

                <form id="loginForm" method="post" action="/login" novalidate>
                    <div id="formStatus" class="alert d-none"></div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <input
                                id="email"
                                name="email"
                                class="form-control <?= cls_invalid($errors,'email') ?>"
                                value="<?= e($values['email'] ?? '') ?>"
                                placeholder="Entrez votre email"
                            >
                        </div>
                        <div class="invalid-feedback" id="emailError">
                            <?= e($errors['email'] ?? '') ?>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label class="form-label">Mot de passe</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text">
                                <i class="bi bi-lock"></i>
                            </span>
                            <input
                                id="password"
                                name="password"
                                type="password"
                                class="form-control <?= cls_invalid($errors,'password') ?>"
                                placeholder="Mot de passe"
                            >
                        </div>
                        <div class="invalid-feedback" id="passwordError">
                            <?= e($errors['password'] ?? '') ?>
                        </div>
                    </div>

                    <!-- Submit -->
                    <button class="btn btn-primary w-100" type="submit">
                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        Se connecter
                    </button>
                </form>

                <div class="text-center mt-3">
                    <a href="/register">Créer un compte</a>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="js/validation-login-ajax.js" defer></script>
</body>
</html>
