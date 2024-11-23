@extends('layouts.app')


@section('content')
    

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="text-xl font-semibold text-purple-100 mb-6">Dashboard</h2>

            

            <!-- Tasks Section -->
            <div class="bg-gray-800 overflow-hidden shadow-lg rounded-lg border border-purple-500/20">
                <div class="p-6">
                    <button id="addTaskBtn" class="mb-6 bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition duration-150 ease-in-out flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Add New Task
                    </button>

                    <!-- Task List -->
                    <div id="taskList" class="space-y-4">
                        @foreach(auth()->user()->tasks()->where('is_deleted', false)->latest()->get() as $task)
                            <div class="task-item flex items-center justify-between p-4 bg-gray-900 rounded-lg border border-purple-500/10 hover:border-purple-500/30 transition duration-150 ease-in-out" 
                                 data-task-id="{{ $task->id }}">
                                <div class="flex items-center space-x-4">
                                    <!-- Favorite Button -->
                                    <button class="favorite-task text-gray-400 hover:text-yellow-500 transition duration-150 {{ $task->is_favorite ? 'text-yellow-500' : '' }}">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    </button>
                                    
                                    <input type="checkbox" 
                                           class="task-checkbox w-4 h-4 rounded border-purple-500 text-purple-600 focus:ring-purple-500 focus:ring-offset-gray-900" 
                                           {{ $task->completed ? 'checked' : '' }}>
                                    <div>
                                        <h3 class="text-lg font-medium text-purple-100 {{ $task->completed ? 'line-through text-purple-400' : '' }}">
                                            {{ $task->title }}
                                        </h3>
                                        @if($task->description)
                                            <p class="text-purple-300">{{ $task->description }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <button class="edit-task text-purple-400 hover:text-purple-300 transition duration-150">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button class="delete-task text-red-400 hover:text-red-300 transition duration-150">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    @include('components.task-modals')

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        let currentTaskId = null;

        // Add Task Button
        document.getElementById('addTaskBtn').addEventListener('click', function() {
            document.getElementById('addTaskModal').classList.remove('hidden');
        });

        // Add Task Form Submit
        document.getElementById('taskForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            fetch('/tasks', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    title: document.getElementById('title').value,
                    description: document.getElementById('description').value
                })
            })
            .then(response => response.json())
            .then(task => {
                // Add new task to the list
                const taskHtml = createTaskElement(task);
                document.getElementById('taskList').insertAdjacentHTML('afterbegin', taskHtml);
                document.getElementById('taskForm').reset();
                document.getElementById('addTaskModal').classList.add('hidden');
            });
        });

        // Close Modal
        document.querySelector('.cancel-modal').addEventListener('click', function() {
            document.getElementById('addTaskModal').classList.add('hidden');
            document.getElementById('taskForm').reset();
        });

        let taskToComplete = null;
        let checkboxClicked = null;

        // Task Checkbox Toggle
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('task-checkbox')) {
                e.preventDefault();
                e.stopPropagation();
                
                checkboxClicked = e.target;
                const taskItem = checkboxClicked.closest('.task-item');
                taskToComplete = taskItem.dataset.taskId;
                
                // Get the current state
                const isCurrentlyCompleted = taskItem.querySelector('h3').classList.contains('line-through');
                
                // Update modal text and buttons based on current state
                const modalTitle = document.querySelector('#completeTaskModal h3');
                const modalText = document.getElementById('completeTaskText');
                const confirmButton = document.querySelector('.confirm-complete');
                const cancelButton = document.querySelector('.cancel-complete');

                if (!isCurrentlyCompleted) {
                    // If task is currently incomplete and user wants to complete it
                    modalTitle.textContent = 'Confirm Task Completion';
                    modalText.textContent = 'Have you finished this task?';
                    confirmButton.textContent = 'Yes, Complete It';
                    confirmButton.classList.remove('bg-yellow-600', 'hover:bg-yellow-700');
                    confirmButton.classList.add('bg-green-600', 'hover:bg-green-700');
                    cancelButton.textContent = 'Not Yet';
                } else {
                    // If task is currently completed and user wants to uncheck
                    modalTitle.textContent = 'Mark Task as Incomplete';
                    modalText.textContent = 'Are you sure you want to mark this task as incomplete?';
                    confirmButton.textContent = 'Yes, Mark as Incomplete';
                    confirmButton.classList.remove('bg-green-600', 'hover:bg-green-700');
                    confirmButton.classList.add('bg-yellow-600', 'hover:bg-yellow-700');
                    cancelButton.textContent = 'Keep Completed';
                }
                
                // Show confirmation modal
                document.getElementById('completeTaskModal').classList.remove('hidden');
            }
        });

        // Cancel Task Completion
        document.querySelector('.cancel-complete').addEventListener('click', function() {
            // Hide modal
            document.getElementById('completeTaskModal').classList.add('hidden');
            // Reset variables
            taskToComplete = null;
            checkboxClicked = null;
        });

        // Confirm Task Completion
        document.querySelector('.confirm-complete').addEventListener('click', function() {
            if (taskToComplete) {
                const taskItem = document.querySelector(`.task-item[data-task-id="${taskToComplete}"]`);
                const titleElement = taskItem.querySelector('h3');
                const checkbox = taskItem.querySelector('.task-checkbox');
                const isCurrentlyCompleted = titleElement.classList.contains('line-through');
                
                fetch(`/tasks/${taskToComplete}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        completed: !isCurrentlyCompleted
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Update task state
                    checkbox.checked = data.task.completed;
                    if (data.task.completed) {
                        titleElement.classList.add('line-through', 'text-purple-400');
                    } else {
                        titleElement.classList.remove('line-through', 'text-purple-400');
                    }

                    // Hide modal and reset variables
                    document.getElementById('completeTaskModal').classList.add('hidden');
                    taskToComplete = null;
                    checkboxClicked = null;
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('completeTaskModal').classList.add('hidden');
                    taskToComplete = null;
                    checkboxClicked = null;
                });
            }
        });

        // Favorite Task Toggle with Confirmation
        document.addEventListener('click', function(e) {
            if (e.target.closest('.favorite-task')) {
                const button = e.target.closest('.favorite-task');
                const taskItem = button.closest('.task-item');
                const taskId = taskItem.dataset.taskId;
                const isFavorite = button.classList.contains('text-yellow-500');
                
                // Update confirmation modal text
                document.getElementById('favoriteConfirmText').textContent = 
                    isFavorite ? 'Remove this task from favorites?' : 'Add this task to favorites?';
                
                // Store task info for confirmation
                currentTaskId = taskId;
                document.getElementById('favoriteConfirmModal').classList.remove('hidden');
            }
        });

        // Favorite Confirmation Actions
        document.querySelector('.confirm-favorite').addEventListener('click', function() {
            if (currentTaskId) {
                fetch(`/tasks/${currentTaskId}/toggle-favorite`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(task => {
                    const button = document.querySelector(`.task-item[data-task-id="${currentTaskId}"] .favorite-task`);
                    if (task.is_favorite) {
                        button.classList.add('text-yellow-500');
                        button.classList.remove('text-gray-400');
                    } else {
                        button.classList.remove('text-yellow-500');
                        button.classList.add('text-gray-400');
                    }
                    document.getElementById('favoriteConfirmModal').classList.add('hidden');
                });
            }
        });

        // Close Favorite Modal
        document.querySelector('.cancel-favorite').addEventListener('click', function() {
            document.getElementById('favoriteConfirmModal').classList.add('hidden');
        });

        // Delete Task
        document.addEventListener('click', function(e) {
            if (e.target.closest('.delete-task')) {
                const taskElement = e.target.closest('.task-item');
                const taskId = taskElement.dataset.taskId;

                fetch(`/tasks/${taskId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(() => {
                    taskElement.remove();
                });
            }
        });

        // Helper function to create new task HTML
        function createTaskElement(task) {
            return `
                <div class="task-item flex items-center justify-between p-4 bg-gray-900 rounded-lg border border-purple-500/10 hover:border-purple-500/30 transition duration-150 ease-in-out" 
                     data-task-id="${task.id}">
                    <div class="flex items-center space-x-4">
                        <button class="favorite-task text-gray-400 hover:text-yellow-500 transition duration-150">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </button>
                        <input type="checkbox" 
                               class="task-checkbox w-4 h-4 rounded border-purple-500 text-purple-600 focus:ring-purple-500 focus:ring-offset-gray-900">
                        <div>
                            <h3 class="text-lg font-medium text-purple-100">${task.title}</h3>
                            ${task.description ? `<p class="text-purple-300">${task.description}</p>` : ''}
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <button class="edit-task text-purple-400 hover:text-purple-300 transition duration-150">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <button class="delete-task text-red-400 hover:text-red-300 transition duration-150">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            `;
        }

        // Edit Task
        document.addEventListener('click', function(e) {
            if (e.target.closest('.edit-task')) {
                const taskItem = e.target.closest('.task-item');
                const taskId = taskItem.dataset.taskId;
                const title = taskItem.querySelector('h3').textContent.trim();
                const description = taskItem.querySelector('p') ? taskItem.querySelector('p').textContent.trim() : '';

                // Populate edit modal
                document.getElementById('editTaskId').value = taskId;
                document.getElementById('editTitle').value = title;
                document.getElementById('editDescription').value = description;

                // Show edit modal
                document.getElementById('editTaskModal').classList.remove('hidden');
            }
        });

        // Edit Task Form Submit
        document.getElementById('editTaskForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const taskId = document.getElementById('editTaskId').value;
            
            fetch(`/tasks/${taskId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    title: document.getElementById('editTitle').value,
                    description: document.getElementById('editDescription').value
                })
            })
            .then(response => response.json())
            .then(task => {
                const taskItem = document.querySelector(`.task-item[data-task-id="${taskId}"]`);
                taskItem.querySelector('h3').textContent = task.title;
                const descElement = taskItem.querySelector('p');
                if (task.description) {
                    if (descElement) {
                        descElement.textContent = task.description;
                    } else {
                        taskItem.querySelector('div').insertAdjacentHTML('beforeend', 
                            `<p class="text-purple-300">${task.description}</p>`);
                    }
                } else if (descElement) {
                    descElement.remove();
                }
                document.getElementById('editTaskModal').classList.add('hidden');
            });
        });

        // Close Edit Modal
        document.querySelector('.cancel-edit').addEventListener('click', function() {
            document.getElementById('editTaskModal').classList.add('hidden');
            document.getElementById('editTaskForm').reset();
        });
    });
    </script>
    @endpush
@endsection
