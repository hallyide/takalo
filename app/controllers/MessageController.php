<?php

class MessageController {

    private static function requireLogin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            Flight::json(['error' => 'Unauthorized'], 401);
            exit;
        }

        return $_SESSION['user']['id'];
    }

    /* ===========================
       Liste des conversations
    =========================== */
    public static function getConversations() {
        $userId = self::requireLogin();

        $repo = new MessageRepository(Flight::db());
        $conversations = $repo->getUserConversations($userId);

        Flight::json($conversations);
    }

    /* ===========================
       Messages d'une conversation
    =========================== */
    public static function getMessages($conversationId) {
        self::requireLogin();

        $repo = new MessageRepository(Flight::db());
        $messages = $repo->getConversationMessages((int)$conversationId);

        Flight::json($messages);
    }

    /* ===========================
       Envoi d'un message
    =========================== */
    public static function sendMessage() {
        $userId = self::requireLogin();

        $data = json_decode(file_get_contents('php://input'), true);

        if (
            empty($data['conversation_id']) ||
            empty($data['message'])
        ) {
            Flight::json(['error' => 'Invalid data'], 400);
            return;
        }

        $repo = new MessageRepository(Flight::db());
        $repo->sendMessage(
            (int)$data['conversation_id'],
            $userId,
            trim($data['message'])
        );

        Flight::json(['success' => true]);
    }

        /* ===========================
       Liste des utilisateurs
       (sauf moi)
    =========================== */
    public static function getUsers() {
        $userId = self::requireLogin();

        $pdo = Flight::db();

        $stmt = $pdo->prepare("
            SELECT id, prenom, nom
            FROM users
            WHERE id != :id
            ORDER BY prenom
        ");
        $stmt->execute(['id' => $userId]);

        Flight::json($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

        /* ===========================
       Créer ou récupérer
       une conversation
    =========================== */
    public static function startConversation() {
        $userId = self::requireLogin();

        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['user_id'])) {
            Flight::json(['error' => 'Invalid user'], 400);
            return;
        }

        $repo = new MessageRepository(Flight::db());

        $conversationId = $repo->getOrCreateConversation(
            $userId,
            (int)$data['user_id']
        );

        Flight::json([
            'conversation_id' => $conversationId
        ]);
    }

}
