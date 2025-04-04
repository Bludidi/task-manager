<?php
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit();
}

  $user_id = $_SESSION['user_id'];
  $query = "SELECT * FROM tasks WHERE user_id = $1";
  $result = pg_query_params($db, $query, array($user_id));
// echo "Welcome to the Task Manager! User ID " . $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h2>Welcome, Task Manager</h2>
    <p>User ID: <?php echo $_SESSION['user_id']; ?> | <a href="logout.php">Logout</a></p>

    <h3>Add a New Task</h3>
    <form id="task-form">
        <label>Title:</label><br>
        <input type="text" name="title" id="title" required><br>
        <label>Description:</label><br>
        <textarea name="description" id="description" required></textarea><br>
        <label>Due Date (optional):</label><br>
        <input type="date" name="due_date" id="due_date"><br>
        <button type="submit">Add Task</button>
    </form>

    <h3>Your Tasks</h3>
    <table border="1">
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Due Date</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody id="task-list">
            <?php
            // $user_id = $_SESSION['user_id'];
            // $query = "SELECT * FROM tasks WHERE user_id = $1";
            // $result = pg_query_params($db, $query, array($user_id));

            if (pg_num_rows($result) > 0) {
                while ($task = pg_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($task['title']) . "</td>";
                    echo "<td>" . htmlspecialchars($task['description']) . "</td>";
                    echo "<td>" . ($task['due_date'] ? $task['due_date'] : 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($task['status']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No tasks yet!</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <script src="js/index.js"></script>
</body>
</html>