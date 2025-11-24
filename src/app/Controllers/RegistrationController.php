<?php
namespace App\Controllers;

use App\Models\User;

// CRITICAL: Since we don't have an autoloader, we must manually include the file
require_once __DIR__ . '/../Models/User.php';

class RegistrationController
{
    /**
     * Show the main registration page (The View)
     */
    public function index()
    {
        // Start output buffering to capture the view content
        ob_start();

        // 1. Load the "home" view (which loads the map & form)
        include __DIR__ . '/../../views/home.php';

        // 2. Get the contents of the buffer and clean it
        $content = ob_get_clean();

        // 3. Load the main layout file.
        include __DIR__ . '/../../views/layout.php';
    }

    /**
     * Handle AJAX submission for Step 1 (The Logic)
     */
    public function submitStep1()
    {
        // Debug logging
        file_put_contents(__DIR__ . '/../../public/debug_log.txt', "Controller: submitStep1 called\n", FILE_APPEND);

        header('Content-Type: application/json');

        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (empty($data['first_name']) || empty($data['last_name']) || empty($data['email'])) {
            echo json_encode([
                'success' => false,
                'message' => 'First Name, Last Name, and Email are required.'
            ]);
            return;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['step1_data'] = $data;

        // Save to Database using the User Model
        $userModel = new User();
        $result = $userModel->create($data);

        if ($result['success']) {
            // CRITICAL: Save the new User ID to session so Step 2 knows who to update
            $_SESSION['user_id'] = $result['id'];

            echo json_encode([
                'success' => true,
                'message' => 'Step 1 saved!',
                'user_id' => $result['id']
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => $result['message']]);
        }
    }

    /**
     * Handle Step 2 (Update User with Photo & Company)
     */
    public function submitStep2()
    {
        header('Content-Type: application/json');

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 1. Security Check: Do we have a User ID from Step 1?
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Session expired. Please refresh and try again.']);
            return;
        }

        $userId = $_SESSION['user_id'];

        // 2. Handle Photo Upload
        $photoPath = null;

        // Check if a file was actually uploaded without errors
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/uploads/';

            // Create upload directory if it doesn't exist
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Generate unique name
            $fileName = time() . '_' . basename($_FILES['photo']['name']);
            $targetPath = $uploadDir . $fileName;

            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (in_array($_FILES['photo']['type'], $allowedTypes)) {
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath)) {
                    $photoPath = 'uploads/' . $fileName;
                }
            }
        }

        // 3. Prepare Data
        $data = [
            'company' => $_POST['company'] ?? '',
            'position' => $_POST['position'] ?? '',
            'about_me' => $_POST['about_me'] ?? '',
            'photo_path' => $photoPath
        ];

        // 4. Update Database
        $userModel = new User();

        if ($userModel->update($userId, $data)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database update failed.']);
        }
    }

    /**
     * Show the "All Members" page
     */
    public function allMembers()
    {
        $userModel = new User();
        $users = $userModel->getAll();

        ob_start();
        include __DIR__ . '/../../views/all-members.php';
        $content = ob_get_clean();
        include __DIR__ . '/../../views/layout.php';
    }
}