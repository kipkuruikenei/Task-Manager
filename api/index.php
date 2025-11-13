<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskMaster Pro | Vercel Deployment</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <header class="header">
            <h1 class="logo">TaskMaster Pro</h1>
            <p class="tagline">Advanced Dynamic Task Management System</p>
        </header>

        <div class="main-content">
            <!-- Task Input Section -->
            <section class="task-input-section">
                <h2>Add New Task</h2>
                <form id="taskForm" class="task-form">
                    <div class="form-group">
                        <input type="text" id="taskTitle" placeholder="Enter task title" required>
                    </div>
                    <div class="form-group">
                        <textarea id="taskDescription" placeholder="Enter task description"></textarea>
                    </div>
                    <div class="form-group">
                        <select id="taskPriority">
                            <option value="low">Low Priority</option>
                            <option value="medium" selected>Medium Priority</option>
                            <option value="high">High Priority</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="date" id="taskDueDate">
                    </div>
                    <button type="submit" class="btn-primary">Add Task</button>
                </form>
            </section>

            <!-- Task Statistics -->
            <section class="stats-section">
                <div class="stats-grid">
                    <div class="stat-card">
                        <h3 id="totalTasks">0</h3>
                        <p>Total Tasks</p>
                    </div>
                    <div class="stat-card">
                        <h3 id="completedTasks">0</h3>
                        <p>Completed</p>
                    </div>
                    <div class="stat-card">
                        <h3 id="pendingTasks">0</h3>
                        <p>Pending</p>
                    </div>
                </div>
            </section>

            <!-- Task List Section -->
            <section class="task-list-section">
                <h2>Task List</h2>
                <div class="filter-controls">
                    <button class="filter-btn active" data-filter="all">All Tasks</button>
                    <button class="filter-btn" data-filter="pending">Pending</button>
                    <button class="filter-btn" data-filter="completed">Completed</button>
                </div>
                <div id="taskList" class="task-list">
                    <!-- Tasks will be loaded here dynamically -->
                    <div class="no-tasks">
                        <p>No tasks found. Add a new task to get started!</p>
                    </div>
                </div>
            </section>
        </div>

        <!-- Loading Spinner -->
        <div id="loadingSpinner" class="loading-spinner" style="display: none;">
            <div class="spinner"></div>
            <p>Loading tasks...</p>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
