<?php

// IMPORT THE CLASS
require_once 'TaskManager.php';

// 1. Initialize the App
$manager = new TaskManager('tasks.json');

// 2. Handle Inputs
if (!isset($argv[1])) {
    echo "\nPlease provide a command (add, list, update, delete...)\n";
    exit(1);
}

$command = $argv[1];
$arguments = array_slice($argv, 2);

// 3. Route Commands
switch ($command) {
    case 'add':
        $description = $arguments[0] ?? null;
        if (!$description) {
            echo "Error: Description is required.\n";
            exit(1);
        }
        $manager->addTask($description);
        break;

    case 'list':
        $filter = $arguments[0] ?? null;
        $manager->listTasks($filter);
        break;

    case 'update':
        $id = $arguments[0] ?? null;
        $desc = $arguments[1] ?? null;
        if (!$id || !$desc) {
            echo "Error: ID and Description required.\n";
            exit(1);
        }
        $manager->updateTask($id, $desc);
        break;

    case 'delete':
        $id = $arguments[0] ?? null;
        if (!$id) {
            echo "Error: ID is required.\n";
            exit(1);
        }
        $manager->deleteTask($id);
        break;

    case 'mark-in-progress':
        $id = $arguments[0] ?? null;
        if (!$id) { echo "Error: ID required.\n"; exit(1); }
        $manager->updateStatus($id, 'in-progress');
        break;

    case 'mark-done':
        $id = $arguments[0] ?? null;
        if (!$id) { echo "Error: ID required.\n"; exit(1); }
        $manager->updateStatus($id, 'done');
        break;

    default:
        echo "Unknown command: $command\n";
        exit(1);
}