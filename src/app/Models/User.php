<?php
namespace App\Models;

use App\Core\Database;
use PDOException;

require_once __DIR__ . '/../Core/Database.php';

class User
{
    private $pdo;

    public function __construct()
    {
        // Get the database connection instance
        $this->pdo = Database::getInstance()->getConnection();
    }

    // --- NEW: Helper to check if email exists (Used by Controller AJAX & createFullUser) ---
    public function emailExists($email)
    {
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return (bool) $stmt->fetch();
    }

    /**
     * Create a user with ALL data
     */
    public function createFullUser(array $data): array
    {
        // 1. Check if email exists (Double check for security)
        // We use the helper function we defined above
        if (!empty($data['email']) && $this->emailExists($data['email'])) {
            return ['success' => false, 'message' => 'Email already registered.'];
        }

        // database
        $sql = "INSERT INTO users (
                    first_name, last_name, birthdate, report_subject, country, phone, email,
                    company, position, about_me, photo_path
                ) VALUES (
                    :first_name, :last_name, :birthdate, :report_subject, :country, :phone, :email,
                    :company, :position, :about_me, :photo_path
                )";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':first_name' => $data['first_name'],
                ':last_name' => $data['last_name'],
                ':birthdate' => $data['birthdate'],
                ':report_subject' => $data['report_subject'],
                ':country' => $data['country'],
                ':phone' => $data['phone'],
                ':email' => $data['email'],
                ':company' => $data['company'] ?? '',
                ':position' => $data['position'] ?? '',
                ':about_me' => $data['about_me'] ?? '',
                ':photo_path' => $data['photo_path'] ?? 'default.jpg'
            ]);

            return ['success' => true, 'id' => $this->pdo->lastInsertId()];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Database Error: ' . $e->getMessage()];
        }
    }

    /**
     * Get all users for the "All Members" page
     */
    public function getAll(): array
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM users ORDER BY created_at DESC");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
}