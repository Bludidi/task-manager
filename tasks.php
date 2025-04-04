<?php
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];

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
                        'id' => $task['id'], // Include ID for edit/delete
                        'title' => $task['title'],
                        'description' => $task['description'],
                        'due_date' => $task['due_date'] ?: null,
                        'status' => $task['status']
                    ];
                }
                echo json_encode(['status' => 'success', 'tasks' => $tasks]);
            } else {
                echo json_encode(['status' => 'error', 'message' => pg_last_error($db)]);
            }
        } elseif ($_POST['action'] == 'edit') {
            $task_id = $_POST['task_id'];
            $title = $_POST['title'];
            $description = $_POST['description'];
            $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : null;
            $status = $_POST['status'];

            $query = "UPDATE tasks SET title = $1, description = $2, due_date = $3, status = $4 
                      WHERE id = $5 AND user_id = $6";
            $result = pg_query_params($db, $query, array($title, $description, $due_date, $status, $task_id, $user_id));

            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'Task updated successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => pg_last_error($db)]);
            }
        } elseif ($_POST['action'] == 'delete') {
            $task_id = $_POST['task_id'];

            $query = "DELETE FROM tasks WHERE id = $1 AND user_id = $2";
            $result = pg_query_params($db, $query, array($task_id, $user_id));

            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'Task deleted successfully']);
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
}
?>