<?php
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit();
}

$query = "SELECT username FROM users WHERE id = $1";
$user_id = $_SESSION['user_id'];
$result = pg_query_params($db, $query, array($user_id));
$user = pg_fetch_assoc($result);
$username = $user ? $user['username'] : 'User';
$query = "SELECT * FROM tasks WHERE user_id = $1";
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
  <h2>Task Manager</h2>
  <p>Welcome back <?php echo htmlspecialchars($username);  ?> | <a href="logout.php">Logout</a></p>

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

  <!-- Edit task modal -->
  <div id="edit-modal" style="display:none;">
    <form id="edit-task-form">
      <h3>Edit Task</h3>
      <input type="hidden" name="task_id" id="edit-task-id">
      <label>Title:</label><br>
      <input type="text" name="title" id="edit-title" required><br>
      <label>Description:</label><br>
      <textarea name="description" id="edit-description" required></textarea><br>
      <label>Due Date (optional):</label><br>
      <input type="date" name="due_date" id="edit-due_date"><br>
      <label>Status:</label><br>
      <select name="status" id="edit-status">
        <option value="Pending">Pending</option>
        <option value="In Progress">In Progress</option>
        <option value="Completed">Completed</option>
      </select><br>
      <button type="submit">Save Changes</button>
      <button type="button" id="cancel-edit">Cancel</button>
    </form>
  </div>

  <script src="js/index.js"></script>
</body>

</html>