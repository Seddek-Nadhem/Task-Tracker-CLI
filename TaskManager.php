<?php

date_default_timezone_set('Asia/Aden');

class TaskManager {
    // Properties
    private $fileName;
    private $tasks = [];

    // Constructor
    public function __construct($fileName) {
        $this->fileName = $fileName;
        $this->checkOrCreateFile();
        $this->loadTasks();
    }

    // --- PRIVATE HELPERS ---

    private function checkOrCreateFile() {
        if (!file_exists($this->fileName)) {
            file_put_contents($this->fileName, '[]');
        }
    }

    private function loadTasks() {
        $content = file_get_contents($this->fileName);
        $this->tasks = json_decode($content, true) ?? [];
    }

    private function saveTasks() {
        $json = json_encode($this->tasks, JSON_PRETTY_PRINT);
        file_put_contents($this->fileName, $json);
    }

    private function getNextId(): int {
        if (count($this->tasks) > 0) {
            $lastTask = end($this->tasks);
            return $lastTask['id'] + 1;
        }
        return 1;
    }

    // --- PUBLIC ACTIONS ---

    public function addTask($description) {
        $id = $this->getNextId();
        $now = date('Y-m-d H:i:s');

        $newTask = [
            'id' => $id,
            'description' => $description,
            'status' => 'todo',
            'createdAt' => $now,
            'updatedAt' => $now
        ];

        $this->tasks[] = $newTask;
        $this->saveTasks();
        echo "Task added successfully (ID: $id)\n";
    }

    public function listTasks($filter = null) {
        $tasksToShow = $this->tasks;

        if ($filter) {
            $tasksToShow = array_filter($this->tasks, function($task) use ($filter) {
                return $task['status'] === $filter;
            });
        }

        if (empty($tasksToShow)) {
            echo "No tasks found" . ($filter ? " with status: $filter" : "") . "!\n";
            return;
        }

        echo "ID  | Status      | Description\n";
        echo "---------------------------------\n";
        foreach ($tasksToShow as $task) {
            printf("%-3s | %-11s | %s\n", $task['id'], $task['status'], $task['description']);
        }
    }

    public function updateTask($id, $description) {
        $found = false;
        foreach ($this->tasks as &$task) {
            if ($task['id'] == $id) {
                $task['description'] = $description;
                $task['updatedAt'] = date('Y-m-d H:i:s');
                $found = true;
                break;
            }
        }

        if ($found) {
            $this->saveTasks();
            echo "Task $id updated successfully.\n";
        } else {
            echo "Task ID $id not found.\n";
        }
    }

    public function deleteTask($id) {
        $initialCount = count($this->tasks);
        
        $this->tasks = array_filter($this->tasks, function($task) use ($id) {
            return $task['id'] != $id;
        });

        if (count($this->tasks) === $initialCount) {
            echo "Task ID $id not found.\n";
        } else {
            $this->tasks = array_values($this->tasks);
            $this->saveTasks();
            echo "Task $id deleted successfully.\n";
        }
    }

    public function updateStatus($id, $status) {
        $found = false;
        foreach ($this->tasks as &$task) {
            if ($task['id'] == $id) {
                $task['status'] = $status;
                $task['updatedAt'] = date('Y-m-d H:i:s');
                $found = true;
                break;
            }
        }

        if ($found) {
            $this->saveTasks();
            echo "Task $id marked as $status.\n";
        } else {
            echo "Task ID $id not found.\n";
        }
    }
}