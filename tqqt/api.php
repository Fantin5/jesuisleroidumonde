<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// Database connection function
function getDatabase() {
    // UPDATE THESE SETTINGS FOR YOUR MySQL
    $host = 'localhost';
    $dbname = 'chef_elodie';
    $username = 'root';  // Change this if needed
    $password = '';      // Change this if needed (often empty for XAMPP)
    
    try {
        $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Create table if not exists
        $db->exec('CREATE TABLE IF NOT EXISTS meals (
            id INT PRIMARY KEY AUTO_INCREMENT,
            title VARCHAR(255) NOT NULL,
            description TEXT NOT NULL,
            images TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )');
        
        return $db;
    } catch(PDOException $e) {
        throw new Exception('Connection failed: ' . $e->getMessage());
    }
}

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

switch ($method) {
    case 'GET':
        if ($action === 'meals') {
            getMeals();
        }
        break;
    
    case 'POST':
        if ($action === 'login') {
            login();
        } elseif ($action === 'add_meal') {
            addMeal();
        }
        break;
    
    case 'DELETE':
        if ($action === 'delete_meal') {
            deleteMeal();
        }
        break;
}

function getMeals() {
    try {
        $db = getDatabase();
        $stmt = $db->query('SELECT * FROM meals ORDER BY created_at DESC');
        $meals = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $row['images'] = json_decode($row['images']);
            $meals[] = $row;
        }
        
        echo json_encode($meals);
    } catch(Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function login() {
    $input = json_decode(file_get_contents('php://input'), true);
    $username = $input['username'] ?? '';
    $password = $input['password'] ?? '';
    
    // HARDCODED LOGIN - NO DATABASE NEEDED
    if ($username === 'test' && $password === '123') {
        echo json_encode(['success' => true, 'message' => 'Login successful']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
    }
}

function addMeal() {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $title = $input['title'] ?? '';
    $description = $input['description'] ?? '';
    $images = json_encode($input['images'] ?? []);
    
    if (empty($title) || empty($description)) {
        echo json_encode(['success' => false, 'message' => 'Title and description are required']);
        return;
    }
    
    try {
        $db = getDatabase();
        $stmt = $db->prepare('INSERT INTO meals (title, description, images) VALUES (?, ?, ?)');
        $stmt->execute([$title, $description, $images]);
        echo json_encode(['success' => true, 'message' => 'Meal added successfully']);
    } catch(Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Failed to add meal: ' . $e->getMessage()]);
    }
}

function deleteMeal() {
    $id = $_GET['id'] ?? '';
    
    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'ID is required']);
        return;
    }
    
    try {
        $db = getDatabase();
        $stmt = $db->prepare('DELETE FROM meals WHERE id = ?');
        $stmt->execute([$id]);
        echo json_encode(['success' => true, 'message' => 'Meal deleted successfully']);
    } catch(Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Failed to delete meal: ' . $e->getMessage()]);
    }
}
?>