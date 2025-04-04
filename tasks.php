<?php
require_once 'db.php'; // Include database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'create') {
            $title = $_POST['title'];
            $description = $_POST['description'];
            $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : null;

            $query = "INSERT INTO tasks (user_id, title, description, due_date, status) 
                      VALUES ($1, $2, $3, $4, 'Pending')";
            $result = pg_query_params($db, $query, array($user_id, $title, $description, $due_date));

            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'Task added successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => pg_last_error($db)]);
            }
        } elseif ($_POST['action'] == 'fetch') {
            $query = "SELECT * FROM tasks WHERE user_id = $1";
            $result = pg_query_params($db, $query, array($user_id));

            if ($result) {
                $tasks = [];
                while ($task = pg_fetch_assoc($result)) {
                    $tasks[] = [
                        'title' => $task['title'],
                        'description' => $task['description'],
                        'due_date' => $task['due_date'],
                        'status' => $task['status']
                    ];
                }
                echo json_encode(['status' => 'success', 'tasks' => $tasks]);
            } else {
                echo json_encode(['status' => 'error', 'message' => pg_last_error($db)]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No action specified']);
    }
    exit();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit();
}
?>