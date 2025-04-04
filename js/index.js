$(document).ready(function() {
  loadTasks();

  // Add task
  $('#task-form').submit(function(e) {
      e.preventDefault();
      if ($('#title').val() === '' || $('#description').val() === '') {
          alert('Title and Description are required!');
          return;
      }
      $.ajax({
          url: 'tasks.php',
          method: 'POST',
          data: $(this).serialize() + '&action=create',
          dataType: 'json',
          success: function(response) {
              if (response.status === 'success') {
                  alert(response.message);
                  $('#task-form')[0].reset();
                  loadTasks();
              } else {
                  alert('Error: ' + response.message);
              }
          },
          error: function(xhr) {
              alert('Failed to add task: ' + xhr.responseText);
          }
      });
  });

  // Edit task
  $(document).on('click', '.edit-task', function() {
      let row = $(this).closest('tr');
      let taskId = row.data('id');
      let title = row.find('td:eq(0)').text();
      let description = row.find('td:eq(1)').text();
      let dueDate = row.find('td:eq(2)').text() === 'N/A' ? '' : row.find('td:eq(2)').text();
      let status = row.find('td:eq(3)').text();

      // Populate edit form
      $('#edit-task-id').val(taskId);
      $('#edit-title').val(title);
      $('#edit-description').val(description);
      $('#edit-due_date').val(dueDate);
      $('#edit-status').val(status);
      $('#edit-modal').show();
  });

  // Save edited task
  $('#edit-task-form').submit(function(e) {
      e.preventDefault();
      $.ajax({
          url: 'tasks.php',
          method: 'POST',
          data: $(this).serialize() + '&action=edit',
          dataType: 'json',
          success: function(response) {
              if (response.status === 'success') {
                  alert(response.message);
                  $('#edit-modal').hide();
                  loadTasks();
              } else {
                  alert('Error: ' + response.message);
              }
          },
          error: function(xhr) {
              alert('Failed to update task: ' + xhr.responseText);
          }
      });
  });

  // Cancel edit
  $('#cancel-edit').click(function() {
      $('#edit-modal').hide();
  });

  // Delete task
  $(document).on('click', '.delete-task', function() {
      if (confirm('Are you sure you want to delete this task?')) {
          let taskId = $(this).closest('tr').data('id');
          $.ajax({
              url: 'tasks.php',
              method: 'POST',
              data: { action: 'delete', task_id: taskId },
              dataType: 'json',
              success: function(response) {
                  if (response.status === 'success') {
                      alert(response.message);
                      loadTasks();
                  } else {
                      alert('Error: ' + response.message);
                  }
              },
              error: function(xhr) {
                  alert('Failed to delete task: ' + xhr.responseText);
              }
          });
      }
  });

  // Load tasks
  function loadTasks() {
      $.ajax({
          url: 'tasks.php',
          method: 'POST',
          data: { action: 'fetch' },
          dataType: 'json',
          success: function(response) {
              if (response.status === 'success') {
                  let tasks = response.tasks;
                  let html = '';
                  if (tasks.length > 0) {
                      tasks.forEach(function(task) {
                          html += '<tr data-id="' + task.id + '">';
                          html += '<td>' + task.title + '</td>';
                          html += '<td>' + task.description + '</td>';
                          html += '<td>' + (task.due_date || 'N/A') + '</td>';
                          html += '<td>' + task.status + '</td>';
                          html += '<td><button class="edit-task">Edit</button> <button class="delete-task">Delete</button></td>';
                          html += '</tr>';
                      });
                  } else {
                      html = '<tr><td colspan="5">No tasks yet!</td></tr>';
                  }
                  $('#task-list').empty().html(html);
              } else {
                  alert('Error loading tasks: ' + response.message);
              }
          },
          error: function(xhr) {
              alert('Failed to load tasks: ' + xhr.responseText);
          }
      });
  }
});