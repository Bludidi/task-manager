$(document).ready(function() {
  loadTasks();

  $('#task-form').submit(function(e) {
      e.preventDefault(); 

      // Basic validation
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
            console.log('Create Response:', response);
              if (response.status === 'success') {
                  alert(response.message); 
                  $('#task-form')[0].reset();
                  loadTasks(); // Clear form
              } else {
                  alert('Error: ' + response.message);
              }
          },
          error: function(xhr, status, error) {
            console.log('Create Error:', xhr.responseText, status, error);
            alert('Failed to add task: ' + xhr.responseText);
        }
      });
  });

  function loadTasks() {
    $.ajax({
        url: 'tasks.php',
        method: 'POST',
        data: { action: 'fetch' },
        dataType: 'json',
        success: function(response) {
          console.log('Fetch Response:', response);
            if (response.status === 'success') {
                let tasks = response.tasks;
                let html = '';
                if (tasks.length > 0) {
                    tasks.forEach(function(task) {
                        html += '<tr>';
                        html += '<td>' + task.title + '</td>';
                        html += '<td>' + task.description + '</td>';
                        html += '<td>' + (task.due_date || 'N/A') + '</td>';
                        html += '<td>' + task.status + '</td>';
                        html += '</tr>';
                    });
                } else {
                    html = '<tr><td colspan="4">No tasks yet!</td></tr>';
                }
                $('#task-list').html(html);
            } else {
                alert('Error loading tasks: ' + response.message);
            }
         } ,
         error: function(xhr, status, error) {
          console.log('Load Tasks Error:', xhr.responseText, status, error);
          alert('Failed to load tasks: ' + xhr.responseText);
         }
    });
  }
});