<?php
namespace App\Controllers;

use App\Models\User;

require_once __DIR__ . '/../Models/User.php';

class RegistrationController
{
    /**
     * Show the main registration page
     */
    public function index()
    {
        ob_start();
        include __DIR__ . '/../../views/home.php';
        $content = ob_get_clean();
        include __DIR__ . '/../../views/layout.php';
    }

    public function checkEmail() // check if email is already used
    {
        header('Content-Type: application/json');

        // Read JSON input from JavaScript
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        $email = $data['email'] ?? '';

        if (empty($email)) {
            echo json_encode(['exists' => false]);
            return;
        }

        $userModel = new User();
        if ($userModel->emailExists($email)) {
            echo json_encode(['exists' => true, 'message' => 'This email is already registered.']);
        } else {
            echo json_encode(['exists' => false]);
        }
    }

    /**
     * Handle the submission
     */
    public function submitFullForm()
    {
        header('Content-Type: application/json');

        // --- BACKEND VALIDATION ---
        $missingFields = [];
        $requiredFields = [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'birthdate' => 'Birthdate',
            'report_subject' => 'Report Subject',
            'country' => 'Country',
            'phone' => 'Phone',
            'email' => 'Email'
        ];

        // Check if required fields are empty
        foreach ($requiredFields as $key => $label) {
            if (empty($_POST[$key])) {
                $missingFields[] = $label;
            }
        }

        // --- EMAIL VALIDATION ---
        if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $missingFields[] = 'Email (Invalid Format)'; // <--- Rejects "bob", accepts "bob@mail.com"
        }

        // --- PHONE VALIDATION ---
        if (!empty($_POST['phone']) && !preg_match('/^[\+]?[\d\s\-\(\)]{10,25}$/', $_POST['phone'])) {
            $missingFields[] = 'Phone (Invalid Format or Length)'; // <--- Rejects "abc" or "+1"
        }

        // If validation fails, stop and return errors
        if (!empty($missingFields)) {
            echo json_encode([
                'success' => false,
                'message' => 'Please fix the following fields: ' . implode(', ', $missingFields)
            ]);
            return;
        }

        // 2. --- PHOTO UPLOAD ---
        $photoPath = 'default.jpg';

        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/uploads/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
                chmod($uploadDir, 0777);
            }

            $fileName = time() . '_' . basename($_FILES['photo']['name']);
            $targetPath = $uploadDir . $fileName;

            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (in_array($_FILES['photo']['type'], $allowedTypes)) {
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath)) {
                    $photoPath = 'uploads/' . $fileName;
                }
            }
        }

        // 3. --- PREPARE DATA ---
        $data = [
            'first_name' => $_POST['first_name'],
            'last_name' => $_POST['last_name'],
            'birthdate' => $_POST['birthdate'],
            'report_subject' => $_POST['report_subject'],
            'country' => $_POST['country'],
            'phone' => $_POST['phone'],
            'email' => $_POST['email'],

            // Step 2 Data
            'company' => $_POST['company'] ?? '',
            'position' => $_POST['position'] ?? '',
            'about_me' => $_POST['about_me'] ?? '',
            'photo_path' => $photoPath
        ];

        // 4. --- SAVE TO DATABASE ---
        $userModel = new User();
        $result = $userModel->createFullUser($data);

        echo json_encode($result);
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