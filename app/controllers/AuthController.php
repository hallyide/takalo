<?php
class AuthController {

  /* =========================
     AFFICHAGE LOGIN
  ========================= */
  public static function showLogin() {
    Flight::render('auth/login', [
      'values' => [
        'email' => ''
      ],
      'errors' => [
        'email' => '',
        'password' => ''
      ],
      'success' => false
    ]);
  }

  /* =========================
     VALIDATION AJAX LOGIN
  ========================= */
  public static function validateLoginAjax() {
    header('Content-Type: application/json; charset=utf-8');

    try {
      $pdo  = Flight::db();
      $repo = new UserRepository($pdo);

      $req = Flight::request();

      $input = [
        'email' => $req->data->email ?? '',
        'password' => $req->data->password ?? '',
      ];

      $res = Validator::validateLogin($input, $repo);

      Flight::json([
        'ok'     => $res['ok'],
        'errors' => $res['errors'],
        'values' => $res['values'],
      ]);
    } catch (Throwable $e) {
      http_response_code(500);
      Flight::json([
        'ok' => false,
        'errors' => [
          // '_global' => $e->getMessage().' '.$e->getFile().' '.$e->getLine()
          '_global' => 'Erreur serveur lors de la validation.'
        ],
        'values' => []
      ]);
    }
  }

  /* =========================
     SOUMISSION LOGIN
  ========================= */
  public static function postLogin() {
    $pdo  = Flight::db();
    $repo = new UserRepository($pdo);
    $svc  = new UserService($repo);

    $req = Flight::request();

    $input = [
      'email' => $req->data->email ?? '',
      'password' => $req->data->password ?? '',
    ];

    $res = Validator::validateLogin($input, $repo);

    if ($res['ok']) {
      $repo = new UserRepository(Flight::db());
      $user = $repo->findByEmail($res['values']['email']);

      if ($user && password_verify($input['password'], $user->getPasswordHash())) {
          session_start();
          $_SESSION['user'] = [
              'id' => $user->getId(),
              'nom' => $user->getNom(),
              'prenom' => $user->getPrenom(),
              'email' => $user->getEmail()
          ];

          // redirection vers /messages
          Flight::redirect('/messages');
          return;
      }


      // identifiants invalides
      $res['ok'] = false;
      $res['errors']['_global'] = 'Email ou mot de passe incorrect.';
    }

    Flight::render('auth/login', [
      'values' => $res['values'],
      'errors' => $res['errors'],
      'success' => false
    ]);
  }


  public static function showRegister() {
    Flight::render('auth/register', [
        'values' => ['nom'=>'','prenom'=>'','email'=>''],
        'errors' => ['nom'=>'','prenom'=>'','email'=>'','password'=>'','confirm_password'=>''],
        'success' => false
    ]);
  }

  public static function validateRegisterAjax() {
    header('Content-Type: application/json; charset=utf-8');

    try {
        $req = Flight::request();
        $repo = new UserRepository(Flight::db());

        $input = [
            'nom' => $req->data->nom,
            'prenom' => $req->data->prenom,
            'email' => $req->data->email,
            'password' => $req->data->password,
            'confirm_password' => $req->data->confirm_password,
        ];

        $res = Validator::validateRegister($input, $repo);

        Flight::json([
            'ok' => $res['ok'],
            'errors' => $res['errors'],
            'values' => $res['values'],
        ]);

    } catch (Throwable $e) {
        http_response_code(500);
        Flight::json(['ok'=>false,'errors'=>['_global'=>'Erreur serveur.'],'values'=>[]]);
    }
  }

  public static function postRegister() {
    $req = Flight::request();
    $repo = new UserRepository(Flight::db());

    $input = [
        'nom' => $req->data->nom,
        'prenom' => $req->data->prenom,
        'email' => $req->data->email,
        'password' => $req->data->password,
        'confirm_password' => $req->data->confirm_password,
    ];

    $res = Validator::validateRegister($input, $repo);

    if ($res['ok']) {
        $hash = password_hash($input['password'], PASSWORD_DEFAULT);
        $userId = $repo->create(
            $res['values']['nom'],
            $res['values']['prenom'],
            $res['values']['email'],
            $hash
        );

        session_start();
        $_SESSION['user'] = [
            'id' => $userId,
            'nom' => $res['values']['nom'],
            'prenom' => $res['values']['prenom'],
            'email' => $res['values']['email']
        ];

        // redirection vers /messages
        Flight::redirect('/messages');
        return;
    }

    Flight::render('auth/register', [
        'values' => $res['values'],
        'errors' => $res['errors'],
        'success' => false
    ]);
  }

  public static function showMessage() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user'])) {
        Flight::redirect('/login');
        return;
    }

    $pdo = Flight::db();

    $messageRepo = new MessageRepository($pdo);
    $userRepo = new UserRepository($pdo);

    $userId = $_SESSION['user']['id'];
    $conversations = $messageRepo->getUserConversations($userId);

    Flight::render('auth/messages', [
        'user' => $_SESSION['user'],
        'conversations' => $conversations
    ]);
  }

  public static function startConversation() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user'])) {
        Flight::json(['error' => 'Unauthorized'], 401);
        return;
    }

    $data = json_decode(file_get_contents('php://input'), true);
    $otherUserId = (int)($data['user_id'] ?? 0);
    $message = trim($data['message'] ?? '');

    if ($otherUserId <= 0 || $message === '') {
        Flight::json(['error' => 'Données invalides'], 400);
        return;
    }

    $pdo = Flight::db();
    $messageRepo = new MessageRepository($pdo);

    $currentUserId = $_SESSION['user']['id'];

    $conversationId = $messageRepo->getOrCreateConversation(
        $currentUserId,
        $otherUserId
    );

    $messageRepo->sendMessage(
        $conversationId,
        $currentUserId,
        $message
    );

    Flight::json([
        'conversation_id' => $conversationId
    ]);
  }
}
