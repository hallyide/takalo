<?php
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/MessageController.php';

require_once __DIR__ . '/services/Validator.php';
require_once __DIR__ . '/services/UserService.php';

require_once __DIR__ . '/repositories/UserRepository.php';
require_once __DIR__ . '/repositories/MessageRepository.php';

/* AUTH */
Flight::route('GET /login', ['AuthController', 'showLogin']);
Flight::route('POST /login', ['AuthController', 'postLogin']);
Flight::route('POST /validate/login', ['AuthController', 'validateLoginAjax']);

Flight::route('GET /register', ['AuthController', 'showRegister']);
Flight::route('POST /register', ['AuthController', 'postRegister']);
Flight::route('POST /validate/register', ['AuthController', 'validateRegisterAjax']);

/* MESSAGES */
Flight::route('GET /messages', ['AuthController', 'showMessage']);

/* API MESSAGES (AJAX / JS) */
Flight::route('GET /api/conversations', ['MessageController', 'getConversations']);
Flight::route('GET /api/messages/@id', ['MessageController', 'getMessages']);
Flight::route('POST /api/messages/send', ['MessageController', 'sendMessage']);

Flight::route('GET /api/users', ['MessageController', 'getUsers']);
Flight::route('POST /api/conversations/start', ['MessageController', 'startConversation']);
Flight::route('POST /api/conversations/e', ['MessageController', 'a']);

