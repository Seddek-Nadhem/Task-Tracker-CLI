# PHP Task Tracker CLI

A simple command-line interface (CLI) application to track your tasks and manage your to-do list. Built with **pure PHP** (no frameworks) using a JSON file for data persistence.

## 🚀 Features

- **Add Tasks:** Create new tasks with a description.
- **List Tasks:** View all tasks or filter by status (`todo`, `in-progress`, `done`).
- **Update Tasks:** Edit the description of existing tasks.
- **Delete Tasks:** Remove tasks permanently.
- **Status Management:** specific commands to mark tasks as `in-progress` or `done`.
- **JSON Storage:** All data is saved automatically to a local `tasks.json` file.
- **Windows Alias:** Includes a `task-cli.bat` file for easy command execution.

## 🛠️ Requirements

- **PHP 8.0** or higher installed on your machine.
- A terminal (Command Prompt, PowerShell, or Git Bash).

## 📥 Installation
1. Clone the repository:
   ```bash
   git clone https://github.com/seddek-nadhem/task-tracker-cli.git
   ```

2- Navigate to the project directory:
   ```bash
   cd task-tracker-cli
   ```

## 💻 Usage
1. Add a Task
  ```bash
task-cli add "Buy groceries"
# Output: Task added successfully (ID: 1)
```
2- Lists Tasks
```bash
task-cli list
# Lists all tasks

task-cli list done
# Lists only tasks with status 'done'
```
3. Update a Task
```bash
task-cli update 1 "Buy healthy groceries"
```
4. Delete a Task
```bash
task-cli delete 1
```
5. Change Status
```bash
task-cli mark-in-progress 1
task-cli mark-done 1
```
## 📂 Project Structure

- `index.php`: The main entry point containing all application logic.
- `tasks.json`: The database file (auto-generated if missing).
- `task-cli.bat`: A batch script to simplify running commands on Windows.

## 📝 Learning Outcomes

- Handling CLI arguments in PHP (`$argv`, `$argc`).
- File I/O operations (Reading/Writing JSON).
- CRUD operations (Create, Read, Update, Delete).
- Data validation and error handling.









