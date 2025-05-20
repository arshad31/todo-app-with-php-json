<?php
$file = 'tasks.json';
$tasks = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

if (isset($_POST['task'])) {
    $newTask = trim($_POST['task']);
    if ($newTask !== '') {
        $tasks[] = [
            'text' => $newTask, 
            'status' => false];
        file_put_contents($file, json_encode($tasks, JSON_PRETTY_PRINT));
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

if (isset($_POST['toggle'])) {
    $index = (int) $_POST['toggle'];
    if (isset($tasks[$index])) {
        $tasks[$index]['status'] = !$tasks[$index]['status'];
        file_put_contents($file, json_encode($tasks, JSON_PRETTY_PRINT));
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

if (isset($_POST['delete'])) {
    $index = (int) $_POST['delete'];
    if (isset($tasks[$index])) {
        unset($tasks[$index]);
        $tasks = array_values($tasks); 
        file_put_contents($file, json_encode($tasks, JSON_PRETTY_PRINT));
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do App</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.min.css">
    <style>
        body { margin-top: 20px; }
        .task-card { border: 1px solid #ececec; padding: 20px; border-radius: 5px; background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .task { color: #888; }
        .task-done { text-decoration: line-through; color: #888; }
        .task-item { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; }
        ul { padding-left: 0; }
        button { cursor: pointer; }
    </style>
</head>
<body>
    <div class="container">
        <div class="task-card">
            <h1>To-Do App</h1>

            <form method="POST">
                <div class="row">
                    <div class="column column-75">
                        <input type="text" name="task" placeholder="Enter a new task" required>
                    </div>
                    <div class="column column-25">
                        <button type="submit" class="button-primary">Add Task</button>
                    </div>
                </div>
            </form>

            <h2>Task List</h2>
            <ul style="list-style: none;">
                <?php if (empty($tasks)){ ?>
                    <li>No tasks yet. Add one above!</li>
                <?php }else{ ?>
                    <?php foreach ($tasks as $index => $task){ ?>
                        <li class="task-item">
                            <form method="POST" style="flex-grow: 1;">
                                <input type="hidden" name="toggle" value="<?= $index ?>">
                                <button type="submit" style="border: none; background: none; cursor: pointer; text-align: left; width: 100%;">
                                    <span class="<?= $task['status'] ? 'task-done' : 'task' ?>">
                                        <?= htmlspecialchars($task['text']) ?>
                                    </span>
                                </button>
                            </form>
                            <form method="POST">
                                <input type="hidden" name="delete" value="<?= $index ?>">
                                <button type="submit" class="button button-outline" style="margin-left: 10px;">Delete</button>
                            </form>
                        </li>
                    <?php } ?>
                <?php } ?>
            </ul>
        </div>
    </div>
</body>
</html>
