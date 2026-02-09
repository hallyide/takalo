<?php

class MessageRepository {

    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /* ===========================
       Récupérer les conversations
       d'un utilisateur
    =========================== */
    public function getUserConversations(int $userId): array {
        $sql = "
            SELECT 
                c.id AS conversation_id,
                CASE 
                    WHEN c.user1_id = :uid THEN u2.id
                    ELSE u1.id
                END AS other_user_id,
                CASE 
                    WHEN c.user1_id = :uid THEN CONCAT(u2.prenom, ' ', u2.nom)
                    ELSE CONCAT(u1.prenom, ' ', u1.nom)
                END AS other_user_name
            FROM conversations c
            JOIN users u1 ON u1.id = c.user1_id
            JOIN users u2 ON u2.id = c.user2_id
            WHERE c.user1_id = :uid OR c.user2_id = :uid
            ORDER BY c.created_at DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['uid' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ===========================
       Messages d'une conversation
    =========================== */
    public function getConversationMessages(int $conversationId): array {
        $sql = "
            SELECT 
                id,
                sender_id,
                message,
                created_at
            FROM messages
            WHERE conversation_id = :cid
            ORDER BY created_at ASC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['cid' => $conversationId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ===========================
       Envoyer un message
    =========================== */
    public function sendMessage(
        int $conversationId,
        int $senderId,
        string $message
    ): void {
        $sql = "
            INSERT INTO messages (conversation_id, sender_id, message)
            VALUES (:cid, :sid, :msg)
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'cid' => $conversationId,
            'sid' => $senderId,
            'msg' => $message
        ]);
    }

    /* ===========================
       Trouver ou créer une
       conversation
    =========================== */
    public function getOrCreateConversation(int $user1, int $user2): int {
        $sql = "
            SELECT id FROM conversations
            WHERE (user1_id = :u1 AND user2_id = :u2)
               OR (user1_id = :u2 AND user2_id = :u1)
            LIMIT 1
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'u1' => $user1,
            'u2' => $user2
        ]);

        $conversation = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($conversation) {
            return (int)$conversation['id'];
        }

        // créer la conversation
        $stmt = $this->pdo->prepare("
            INSERT INTO conversations (user1_id, user2_id)
            VALUES (:u1, :u2)
        ");
        $stmt->execute([
            'u1' => $user1,
            'u2' => $user2
        ]);

        return (int)$this->pdo->lastInsertId();
    }
}
