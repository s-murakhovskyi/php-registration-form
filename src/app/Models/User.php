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

    // Create new user (Step 1)
    public function create(array $data): array
    {
        // Check if email exists (Unique email requirement)
        if (!empty($data['email'])) {
            $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$data['email']]);
            if ($stmt->fetch()) {
                return ['success' => false, 'message' => 'Email already registered.'];
            }
        }

        // 2. Insert new user
        $sql = "INSERT INTO users (first_name, last_name, birthdate, report_subject, country, phone, email) 
                VALUES (:first_name, :last_name, :birthdate, :report_subject, :country, :phone, :email)";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':first_name' => $data['first_name'],
                ':last_name' => $data['last_name'],
                ':birthdate' => $data['birthdate'],
                ':report_subject' => $data['report_subject'],
                ':country' => $data['country'],
                ':phone' => $data['phone'],
                ':email' => $data['email'] ?: null
            ]);

            // Return the ID so we can update this user in Step 2
            return ['success' => true, 'id' => $this->pdo->lastInsertId()];
        } catch (PDOException $e) {
            // Log the actual error for debugging if needed
            return ['success' => false, 'message' => 'Database Error: ' . $e->getMessage()];
        }
    }

    // Update user (Step 2)
    public function update($id, array $data): bool
    {
        $sql = "UPDATE users 
                SET company = :company, 
                    position = :position, 
                    about_me = :about_me, 
                    photo_path = :photo_path 
                WHERE id = :id";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':company' => $data['company'] ?? null,
                ':position' => $data['position'] ?? null,
                ':about_me' => $data['about_me'] ?? null,
                ':photo_path' => $data['photo_path'] ?? 'default.jpg',
                ':id' => $id
            ]);

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Get all users for the "All Members" page
    public function getAll(): array
    {
        try {
            // Select all columns, ordered by newest first
            $stmt = $this->pdo->query("SELECT * FROM users ORDER BY created_at DESC");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
}