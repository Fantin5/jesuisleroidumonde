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

// Helper function to validate category
function validateCategory($category) {
    $validCategories = ['cat1', 'cat2', 'cat3'];
    return in_array($category, $validCategories) ? $category : 'cat1';
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
            // Check if category filter is requested
            $categoryFilter = $_GET['category'] ?? '';
            
            if ($categoryFilter && in_array($categoryFilter, ['cat1', 'cat2', 'cat3'])) {
                $stmt = $pdo->prepare("SELECT * FROM meals WHERE category = ? ORDER BY created_at DESC");
                $stmt->execute([$categoryFilter]);
            } else {
                $stmt = $pdo->query("SELECT * FROM meals ORDER BY created_at DESC");
            }
            
            $meals = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($meals as &$meal) {
                $meal['images'] = json_decode($meal['images'], true) ?? [];
                // Ensure category has a default value
                if (empty($meal['category'])) {
                    $meal['category'] = 'cat1';
                }
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
                // Ensure category has a default value
                if (empty($meal['category'])) {
                    $meal['category'] = 'cat1';
                }
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
        
        // Get bilingual fields and category
        $title_en = $_POST['title_en'] ?? '';
        $title_fr = $_POST['title_fr'] ?? '';
        $description_en = $_POST['description_en'] ?? '';
        $description_fr = $_POST['description_fr'] ?? '';
        $category = validateCategory($_POST['category'] ?? 'cat1');
        
        if (empty($title_en) || empty($title_fr) || empty($description_en) || empty($description_fr)) {
            echo json_encode(['success' => false, 'message' => 'All language fields are required']);
            break;
        }
        
        try {
            $images = [];
            if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
                $images = uploadImages($_FILES['images']);
            }
            
            $stmt = $pdo->prepare("INSERT INTO meals (title_en, title_fr, description_en, description_fr, category, title, description, images) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $title_en, 
                $title_fr, 
                $description_en, 
                $description_fr,
                $category,
                $title_en, // Fallback for old code compatibility
                $description_en, // Fallback for old code compatibility
                json_encode($images)
            ]);
            
            echo json_encode(['success' => true, 'message' => 'Meal added successfully']);
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Failed to add meal: ' . $e->getMessage()]);
        }
        break;
        
    case 'update_meal':
        // Check authentication
        if (!isset($_SESSION['admin_logged_in'])) {
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            break;
        }
        
        $id = $_POST['id'] ?? '';
        $title_en = $_POST['title_en'] ?? '';
        $title_fr = $_POST['title_fr'] ?? '';
        $description_en = $_POST['description_en'] ?? '';
        $description_fr = $_POST['description_fr'] ?? '';
        $category = validateCategory($_POST['category'] ?? 'cat1');
        $existingImages = json_decode($_POST['existing_images'] ?? '[]', true);
        
        if (!is_numeric($id) || empty($title_en) || empty($title_fr) || empty($description_en) || empty($description_fr)) {
            echo json_encode(['success' => false, 'message' => 'Invalid data - all language fields are required']);
            break;
        }
        
        try {
            $allImages = $existingImages;
            
            if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
                $newImages = uploadImages($_FILES['images']);
                $allImages = array_merge($allImages, $newImages);
            }
            
            $stmt = $pdo->prepare("UPDATE meals SET title_en = ?, title_fr = ?, description_en = ?, description_fr = ?, category = ?, title = ?, description = ?, images = ? WHERE id = ?");
            $stmt->execute([
                $title_en, 
                $title_fr, 
                $description_en, 
                $description_fr,
                $category,
                $title_en, // Fallback for old code compatibility
                $description_en, // Fallback for old code compatibility
                json_encode($allImages), 
                $id
            ]);
            
            echo json_encode(['success' => true, 'message' => 'Meal updated successfully']);
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Failed to update meal: ' . $e->getMessage()]);
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
        
    case 'get_categories':
        // Optional endpoint to get category statistics
        try {
            $stmt = $pdo->query("
                SELECT 
                    category,
                    COUNT(*) as count
                FROM meals 
                GROUP BY category
                ORDER BY category
            ");
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Ensure all categories are represented
            $allCategories = [
                'cat1' => 0,
                'cat2' => 0,
                'cat3' => 0
            ];
            
            foreach ($categories as $cat) {
                $allCategories[$cat['category']] = intval($cat['count']);
            }
            
            echo json_encode($allCategories);
        } catch(PDOException $e) {
            echo json_encode(['error' => 'Failed to fetch categories']);
        }
        break;
        
    case 'comments':
        // Check authentication
        if (!isset($_SESSION['admin_logged_in'])) {
            echo json_encode(['error' => 'Not authenticated']);
            break;
        }
        
        try {
            $stmt = $pdo->query("SELECT * FROM comments ORDER BY created_at DESC");
            $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($comments);
        } catch(PDOException $e) {
            echo json_encode(['error' => 'Failed to fetch comments']);
        }
        break;
        
    case 'approved_comments':
        try {
            $stmt = $pdo->query("SELECT * FROM comments ORDER BY created_at DESC");
            $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($comments);
        } catch(PDOException $e) {
            echo json_encode(['error' => 'Failed to fetch comments']);
        }
        break;
        
    case 'get_comment':
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
            $stmt = $pdo->prepare("SELECT * FROM comments WHERE id = ?");
            $stmt->execute([$id]);
            $comment = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($comment) {
                echo json_encode($comment);
            } else {
                echo json_encode(['error' => 'Comment not found']);
            }
        } catch(PDOException $e) {
            echo json_encode(['error' => 'Database error']);
        }
        break;
        
    case 'add_comment':
        $name_en = $_POST['name_en'] ?? '';
        $name_fr = $_POST['name_fr'] ?? '';
        $comment_en = $_POST['comment_en'] ?? '';
        $comment_fr = $_POST['comment_fr'] ?? '';
        
        if (empty($name_en) || empty($name_fr) || empty($comment_en) || empty($comment_fr)) {
            echo json_encode(['success' => false, 'message' => 'All fields are required']);
            break;
        }
        
        try {
            $stmt = $pdo->prepare("INSERT INTO comments (name_en, name_fr, comment_en, comment_fr) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name_en, $name_fr, $comment_en, $comment_fr]);
            
            echo json_encode(['success' => true, 'message' => 'Comment submitted successfully']);
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Failed to submit comment']);
        }
        break;
        
    case 'delete_comment':
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
            $stmt = $pdo->prepare("DELETE FROM comments WHERE id = ?");
            $stmt->execute([$id]);
            
            echo json_encode(['success' => true, 'message' => 'Comment deleted successfully']);
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Failed to delete comment']);
        }
        break;

    case 'update_comment':
        // Check authentication
        if (!isset($_SESSION['admin_logged_in'])) {
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            break;
        }
        
        $id = $_POST['id'] ?? '';
        $name_en = $_POST['name_en'] ?? '';
        $name_fr = $_POST['name_fr'] ?? '';
        $comment_en = $_POST['comment_en'] ?? '';
        $comment_fr = $_POST['comment_fr'] ?? '';
        
        if (!is_numeric($id) || empty($name_en) || empty($name_fr) || empty($comment_en) || empty($comment_fr)) {
            echo json_encode(['success' => false, 'message' => 'Invalid data - all fields are required']);
            break;
        }
        
        try {
            $stmt = $pdo->prepare("UPDATE comments SET name_en = ?, name_fr = ?, comment_en = ?, comment_fr = ? WHERE id = ?");
            $stmt->execute([$name_en, $name_fr, $comment_en, $comment_fr, $id]);
            
            echo json_encode(['success' => true, 'message' => 'Comment updated successfully']);
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Failed to update comment: ' . $e->getMessage()]);
        }
        break;
    
    default:
        echo json_encode(['error' => 'Invalid action']);
        break;
}
?>