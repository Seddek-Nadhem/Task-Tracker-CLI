<?php 

date_default_timezone_set('Asia/Aden'); // Or your specific timezone

$tasksFile = 'tasks.json';

function checkOrCreateFile($fileName) {
    if (!file_exists($fileName)) {
        file_put_contents($fileName, '[]');
    }
}

checkOrCreateFile($tasksFile);

// From JSON to ARRAY
function readTasks($fileName) {
    $content = file_get_contents($fileName);
    
    // Returns file content as a PHP array.
    // TRUE forces it to be an array.
    $tasks = json_decode($content, true);

    return $tasks ?? []; 
}

// From ARRAY to JSON
function saveTasks($fileName, $tasks) {
    $json = json_encode($tasks, JSON_PRETTY_PRINT);

    file_put_contents($fileName, $json);
}

function getOrSetID($tasksArray): int {
    if (count($tasksArray) > 0) {
        $lastTask = end($tasksArray);
        return $lastTask['id'] + 1;
    } else {
        return 1;
    }
}

function showAllTasks($tasksArray) {
    if (count($tasksArray) === 0) {
            echo "No tasks found!\n";
        } else {
            echo "ID  | Status      | Description\n";
            echo "---------------------------------\n";
            foreach ($tasksArray as $task) {
                printf("%-3s | %-11s | %s\n", $task['id'], $task['status'], $task['description']);
            }
        }
}

function showOneTask($task) {
    printf("%-3s | %-11s | %s\n", $task['id'], $task['status'], $task['description']);
}

if (!isset($argv[1])) {
    echo "\nPlease provide a command (add, update, delete, list etc...)\n";
    exit(1);
}

function createNewTask($id, $now, $description) {
    return [
        'id' => $id,
        'description' => $description,
        'status' => 'todo',
        'createdAt' => $now,
        'updatedAt' => $now
    ];
}

$command = $argv[1];
$arguments = array_slice($argv, 2);

switch ($command) {
    case 'add':
        $arguments[0] ?? null;
        if (!$arguments) {
            echo "You have to add a task after the command add!\n";
            exit(1);
        }
        
        $tasks = readTasks($tasksFile);

        $id = getOrSetID($tasks);
        $now = date('Y-m-d H:i:s');
        $description = $arguments[0];

        $newTask = createNewTask($id, $now, $description);
        $tasks[] = $newTask;

        saveTasks($tasksFile, $tasks);
        echo "Task added successfully (ID: $id)\n";
        
        break;

    case 'list':
        $tasks = readTasks($tasksFile);
        $filter = $arguments[0] ?? null;
        if ($filter) {
            $tasks = array_filter($tasks, function($task) use ($filter){
                return $task['status'] === $filter;
            });
            // a shorter but more eye hurting way to write the same code!
            // $tasks = array_filter(fn($task) => $task['status'] === $filter);
        }
        showAllTasks($tasks);
        break;

    case 'update':
        // 1. Validate inputs (We need an ID and Text)
        $idToUpdate = $arguments[0] ?? null;
        $newDescription = $arguments[1] ?? null;

        if (!$idToUpdate || !$newDescription) {
            echo "Error: Please provide an ID and a new description.\n";
            echo "Example: task-cli update 1 \"New Task Name\"\n";
            exit(1);
        }

        // 2. Read tasks
        $tasks = readTasks($tasksFile);
        $found = false;

        foreach ($tasks as &$task) {
            if ($task['id'] == $idToUpdate) {
                $task['description'] = $newDescription;
                $task['updatedAt'] = date('Y-m-d H:i:s'); 
                $found = true;
                break; 
            }
        }
        
        if ($found) {
            saveTasks($tasksFile, $tasks);
            echo "Task $idToUpdate updated successfully!\n";
        } else {
            echo "Task with ID $idToUpdate not found.\n";
        }
        break;

    case 'delete':
        $idToDelete = $arguments[0] ?? null;
        if (!$idToDelete) {
            echo "Error: Please provide an ID to delete.\n";
            exit(1);
        }
        // Get the tasks
        $tasks = readTasks($tasksFile);
        // The real count to compare later
        $initialCount = count($tasks);
        // New tasks array to save in the file. 
        $tasks = array_filter($tasks, function($task) use ($idToDelete) {
            return $task['id'] != $idToDelete;
        });

        if (count($tasks) === $initialCount) {
            echo "Task with ID $idToDelete not found.\n";
        } else {
            $tasks = array_values($tasks);
            
            saveTasks($tasksFile, $tasks);
            echo "Task $idToDelete deleted successfully.\n";
        }
        break;

    case 'mark-in-progress':
        $id = $arguments[0] ?? null;
        if (!$id) {
            echo "Error: Please provide an ID.\n";
            exit(1);
        }

        $tasks = readTasks($tasksFile);
        $found = false;

        foreach($tasks as &$task) {
            if ($task['id'] == $id) {
                $task['status'] = 'in-progress';
                $task['updatedAt'] = date('Y-m-d H:i:s');
                $found = true;
                break;
            }
        }

        if ($found) {
            saveTasks($tasksFile, $tasks);
            echo "Task $id marked as IN-PROGRESS.\n";
        } else {
            echo "Task not found.\n";
        }
        break;

    case 'mark-done':
        $id = $arguments[0] ?? null; 
        if (!$id) {
            echo "Error: Provide an ID please.\n";
            exit(1);
        }

        $tasks = readTasks($tasksFile);
        $found = false;

        foreach($tasks as &$task) {
            if ($task['id'] == $id) {
                $task['status'] = 'done';
                $task['updatedAt'] = date('Y-m-d H:i:s');
                $found = true;
                break;
            }
        }

        if ($found) {
            saveTasks($tasksFile, $tasks);
            echo "Task status updated successfully!\n";
        } else {
            echo "Task ID was no found! Enter a valid task ID please.\n";
        }
        break;

    default:
        echo "Unknown Command: $command\n";
        exit(1);
}