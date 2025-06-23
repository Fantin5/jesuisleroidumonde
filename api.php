<?php
session_start();
header('Content-Type: application/json');

// Database configuration - CHANGE THESE TO YOUR DATABASE SETTINGS
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "chef_marie_db";

// Admin credentials
$admin_username = "test";
$admin_password = "123";

// Create connection
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed. Please check your database settings.']);
    exit();
}

// Helper function to create uploads directory
function ensureUploadsDirectory() {
    $uploadDir = 'uploads/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    return $uploadDir;
}

// Helper function to upload images
function uploadImages($files) {
    $uploadDir = ensureUploadsDirectory();
    $uploadedFiles = [];
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $maxFileSize = 5 * 1024 * 1024; // 5MB
    
    if (!is_array($files['name'])) {
        $files = [
            'name' => [$files['name']],
            'type' => [$files['type']],
            'tmp_name' => [$files['tmp_name']],
            'error' => [$files['error']],
            'size' => [$files['size']]
        ];
    }
    
    for ($i = 0; $i < count($files['name']); $i++) {
        if ($files['error'][$i] === UPLOAD_ERR_OK) {
            $fileType = $files['type'][$i];
            $fileSize = $files['size'][$i];
            
            if (in_array($fileType, $allowedTypes) && $fileSize <= $maxFileSize) {
                $fileExtension = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
                $filename = uniqid() . '_' . time() . '.' . $fileExtension;
                $filepath = $uploadDir . $filename;
                
                if (move_uploaded_file($files['tmp_name'][$i], $filepath)) {
                    $uploadedFiles[] = $filepath;
                }
            }
        }
    }
    
    return $uploadedFiles;
}

// Get the action parameter
$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
    case 'login':
        $username_input = $_POST['username'] ?? '';
        $password_input = $_POST['password'] ?? '';
        
        if ($username_input === $admin_username && $password_input === $admin_password) {
            $_SESSION['admin_logged_in'] = true;
            echo json_encode(['success' => true, 'message' => 'Login successful']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
        }
        break;
        
    case 'logout':
        session_destroy();
        echo json_encode(['success' => true]);
        break;
        
    case 'check_auth':
        $authenticated = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
        echo json_encode(['authenticated' => $authenticated]);
        break;
        
    case 'meals':
        try {
            $stmt = $pdo->query("SELECT * FROM meals ORDER BY created_at DESC");
            $meals = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($meals as &$meal) {
                $meal['images'] = json_decode($meal['images'], true) ?? [];
            }
            
            echo json_encode($meals);
        } catch(PDOException $e) {
            echo json_encode(['error' => 'Failed to fetch meals']);
        }
        break;
        
    case 'get_meal':
        // Check authentication
        if (!isset($_SESSION['admin_logged_in'])) {
            echo json_encode(['error' => 'Not authenticated']);
            break;
        }
        
        $id = $_GET['id'] ?? '';
        if (!is_numeric($id)) {
            echo json_encode(['error' => 'Invalid ID']);
            break;
        }
        
        try {
            $stmt = $pdo->prepare("SELECT * FROM meals WHERE id = ?");
            $stmt->execute([$id]);
            $meal = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($meal) {
                $meal['images'] = json_decode($meal['images'], true) ?? [];
                echo json_encode($meal);
            } else {
                echo json_encode(['error' => 'Meal not found']);
            }
        } catch(PDOException $e) {
            echo json_encode(['error' => 'Database error']);
        }
        break;
        
    case 'add_meal':
        // Check authentication
        if (!isset($_SESSION['admin_logged_in'])) {
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            break;
        }
        
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        
        if (empty($title) || empty($description)) {
            echo json_encode(['success' => false, 'message' => 'Title and description are required']);
            break;
        }
        
        try {
            $images = [];
            if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
                $images = uploadImages($_FILES['images']);
            }
            
            $stmt = $pdo->prepare("INSERT INTO meals (title, description, images) VALUES (?, ?, ?)");
            $stmt->execute([$title, $description, json_encode($images)]);
            
            echo json_encode(['success' => true, 'message' => 'Meal added successfully']);
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Failed to add meal']);
        }
        break;
        
    case 'update_meal':
        // Check authentication
        if (!isset($_SESSION['admin_logged_in'])) {
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            break;
        }
        
        $id = $_POST['id'] ?? '';
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $existingImages = json_decode($_POST['existing_images'] ?? '[]', true);
        
        if (!is_numeric($id) || empty($title) || empty($description)) {
            echo json_encode(['success' => false, 'message' => 'Invalid data']);
            break;
        }
        
        try {
            $allImages = $existingImages;
            
            if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
                $newImages = uploadImages($_FILES['images']);
                $allImages = array_merge($allImages, $newImages);
            }
            
            $stmt = $pdo->prepare("UPDATE meals SET title = ?, description = ?, images = ? WHERE id = ?");
            $stmt->execute([$title, $description, json_encode($allImages), $id]);
            
            echo json_encode(['success' => true, 'message' => 'Meal updated successfully']);
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Failed to update meal']);
        }
        break;
        
    case 'delete_meal':
        // Check authentication
        if (!isset($_SESSION['admin_logged_in'])) {
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            break;
        }
        
        $id = $_POST['id'] ?? '';
        if (!is_numeric($id)) {
            echo json_encode(['success' => false, 'message' => 'Invalid ID']);
            break;
        }
        
        try {
            // Get images to delete
            $stmt = $pdo->prepare("SELECT images FROM meals WHERE id = ?");
            $stmt->execute([$id]);
            $meal = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($meal) {
                $images = json_decode($meal['images'], true) ?? [];
                foreach ($images as $image) {
                    if (file_exists($image)) {
                        unlink($image);
                    }
                }
            }
            
            // Delete meal
            $stmt = $pdo->prepare("DELETE FROM meals WHERE id = ?");
            $stmt->execute([$id]);
            
            echo json_encode(['success' => true, 'message' => 'Meal deleted successfully']);
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Failed to delete meal']);
        }
        break;
        
    default:
        echo json_encode(['error' => 'Invalid action']);
        break;
}
?>