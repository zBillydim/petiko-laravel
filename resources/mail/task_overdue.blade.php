<!DOCTYPE html>
<html>
<head>
    <title>Task Overdue/title>
</head>
<body>
    <h1>Your Task is Overdue</h1>
    <p>{{ $task->user->name }},</p>
    <p>Your task "{{ $task->task_title }}" has passed its due date and is now overdue.</p>
    <p>Get up and go complete the task, remember keeping up to date is essential for a better life</p>
    <p>Thank you!</p>
</body>
</html>
