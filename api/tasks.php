<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// Use secure path for data storage
$dataFile = __DIR__ . '/../private/tasks.json';

// Ensure private directory exists
$privateDir = __DIR__ . '/../private';
if (!file_exists($privateDir)) {
    mkdir($privateDir, 0755, true);
}

// Initialize tasks file if it doesn't exist
if (!file_exists($dataFile)) {
    file_put_contents($dataFile, json_encode([]));
}

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Helper function to read tasks
function readTasks() {
    global $dataFile;
    if (!file_exists($dataFile)) {
        return [];
    }
    $data = file_get_contents($dataFile);
    return json_decode($data, true) ?: [];
}

// Helper function to save tasks
function saveTasks($tasks) {
    global $dataFile;
    return file_put_contents($dataFile, json_encode($tasks, JSON_PRETTY_PRINT));
}

// Get input data
if ($method == 'POST' || $method == 'PUT' || $method == 'DELETE') {
    $input = json_decode(file_get_contents('php://input'), true);
} else {
    $input = [];
}

// Handle different HTTP methods
switch ($method) {
    case 'GET':
        $tasks = readTasks();
        echo json_encode([
            'success' => true,
            'tasks' => $tasks,
            'total' => count($tasks),
            'completed' => count(array_filter($tasks, fn($task) => $task['completed'])),
            'pending' => count(array_filter($tasks, fn($task) => !$task['completed']))
        ]);
        break;

    case 'POST':
        if (!empty($input['title'])) {
            $tasks = readTasks();
            $newTask = [
                'id' => uniqid(),
                'title' => htmlspecialchars(trim($input['title'])),
                'description' => htmlspecialchars(trim($input['description'] ?? '')),
                'priority' => in_array($input['priority'] ?? 'medium', ['low', 'medium', 'high']) ? $input['priority'] : 'medium',
                'dueDate' => $input['dueDate'] ?? null,
                'completed' => false,
                'createdAt' => date('Y-m-d H:i:s')
            ];
            
            $tasks[] = $newTask;
            if (saveTasks($tasks)) {
                echo json_encode(['success' => true, 'task' => $newTask]);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'error' => 'Failed to save task']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Task title is required']);
        }
        break;

    case 'PUT':
        $taskId = $input['id'] ?? '';
        
        if ($taskId) {
            $tasks = readTasks();
            $updated = false;
            
            foreach ($tasks as &$task) {
                if ($task['id'] === $taskId) {
                    $task['completed'] = !$task['completed'];
                    $updated = true;
                    break;
                }
            }
            
            if ($updated && saveTasks($tasks)) {
                echo json_encode(['success' => true]);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'error' => 'Failed to update task']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Task ID is required']);
        }
        break;

    case 'DELETE':
        $taskId = $input['id'] ?? '';
        
        if ($taskId) {
            $tasks = readTasks();
            $initialCount = count($tasks);
            $tasks = array_filter($tasks, fn($task) => $task['id'] !== $taskId);
            
            if (count($tasks) < $initialCount && saveTasks(array_values($tasks))) {
                echo json_encode(['success' => true]);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'error' => 'Failed to delete task']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Task ID is required']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'error' => 'Method not allowed']);
        break;
}
?>