<!-- Add Task Modal -->
<div id="addTaskModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 hidden overflow-y-auto h-full w-full" x-show="open">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-gray-800 border-purple-500/20">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-purple-100 text-center">Add New Task</h3>
            <form id="taskForm" class="mt-4">
                <div class="mb-4">
                    <label class="block text-purple-200 text-sm font-medium mb-2" for="title">
                        Task Title
                    </label>
                    <input type="text" id="title" name="title" 
                           class="w-full bg-gray-700 border-purple-500/30 rounded-lg text-purple-100 focus:ring-purple-500 focus:border-purple-500">
                </div>
                <div class="mb-4">
                    <label class="block text-purple-200 text-sm font-medium mb-2" for="description">
                        Description (Optional)
                    </label>
                    <textarea id="description" name="description" rows="3" 
                              class="w-full bg-gray-700 border-purple-500/30 rounded-lg text-purple-100 focus:ring-purple-500 focus:border-purple-500"></textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" class="cancel-modal px-4 py-2 bg-gray-700 text-purple-200 rounded-lg hover:bg-gray-600">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                        Add Task
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Task Modal -->
<div id="editTaskModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-gray-800 border-purple-500/20">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-purple-100 text-center">Edit Task</h3>
            <form id="editTaskForm" class="mt-4">
                <input type="hidden" id="editTaskId">
                <div class="mb-4">
                    <label class="block text-purple-200 text-sm font-medium mb-2" for="editTitle">
                        Task Title
                    </label>
                    <input type="text" id="editTitle" name="title" 
                           class="w-full bg-gray-700 border-purple-500/30 rounded-lg text-purple-100 focus:ring-purple-500 focus:border-purple-500">
                </div>
                <div class="mb-4">
                    <label class="block text-purple-200 text-sm font-medium mb-2" for="editDescription">
                        Description (Optional)
                    </label>
                    <textarea id="editDescription" name="description" rows="3" 
                              class="w-full bg-gray-700 border-purple-500/30 rounded-lg text-purple-100 focus:ring-purple-500 focus:border-purple-500"></textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" class="cancel-edit px-4 py-2 bg-gray-700 text-purple-200 rounded-lg hover:bg-gray-600">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                        Update Task
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteConfirmModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-gray-800 border-purple-500/20">
        <div class="mt-3 text-center">
            <h3 class="text-lg font-medium text-purple-100">Confirm Delete</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-purple-200">Are you sure you want to move this task to trash?</p>
            </div>
            <div class="flex justify-center space-x-2">
                <button class="cancel-delete px-4 py-2 bg-gray-700 text-purple-200 rounded-lg hover:bg-gray-600">
                    Cancel
                </button>
                <button class="confirm-delete px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Favorite Confirmation Modal -->
<div id="favoriteConfirmModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-gray-800 border-purple-500/20">
        <div class="mt-3 text-center">
            <h3 class="text-lg font-medium text-purple-100">Confirm Action</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-purple-200" id="favoriteConfirmText">Do you want to mark this task as favorite?</p>
            </div>
            <div class="flex justify-center space-x-2">
                <button class="cancel-favorite px-4 py-2 bg-gray-700 text-purple-200 rounded-lg hover:bg-gray-600">
                    Cancel
                </button>
                <button class="confirm-favorite px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">
                    Confirm
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Complete Task Confirmation Modal -->
<div id="completeTaskModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-gray-800 border-purple-500/20">
        <div class="mt-3 text-center">
            <h3 class="text-lg font-medium text-purple-100">Confirm Task Completion</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-purple-200" id="completeTaskText">Have you finished this task?</p>
            </div>
            <div class="flex justify-center space-x-2">
                <button class="cancel-complete px-4 py-2 bg-gray-700 text-purple-200 rounded-lg hover:bg-gray-600">
                    Not Yet
                </button>
                <button class="confirm-complete px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Yes, Complete It
                </button>
            </div>
        </div>
    </div>
</div> 