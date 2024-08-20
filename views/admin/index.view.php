<?php require base_path('views/partials/head.php') ?>

<?php require base_path('views/partials/nav.php') ?>

<?php require base_path('views/partials/banner.php') ?>


    <main>
        <div class="mx-auto max-w-7xl bg-white py-6 sm:px-6 lg:px-8 mt-10">
            <!--<div class="flex items-center justify-between mb-4">
                <h1 class="text-2xl font-semibold text-gray-800">Task Management</h1>
                <div>
                    <button class="filter-btn bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600"
                            data-status="all">All
                    </button>
                    <button class="filter-btn bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600"
                            data-status="2">In Progress
                    </button>
                    <button class="filter-btn bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600"
                            data-status="3">Help
                    </button>
                    <button class="filter-btn bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600"
                            data-status="4">Completed
                    </button>
                </div>
            </div>-->

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md">
                    <thead class="bg-blue-500 text-white">
                    <tr>
                        <th class="py-3 px-6 text-left">Task ID</th>
                        <th class="py-3 px-6 text-left">Task title</th>
                        <th class="py-3 px-6 text-left">Assigned To</th>
                        <th class="py-3 px-6 text-left">Status</th>
                        <th class="py-3 px-6 text-left">Action</th>
                    </tr>
                    </thead>
                    <tbody id="tasks-body">
                    <?php foreach ($tasks as $task) : ?>
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6 text-left"><?= $task["task_id"] ?></td>
                            <td class="py-3 px-6 text-left"><?= $task["task_title"] ?></td>
                            <td class="py-3 px-6 text-left"><?= $task["user_email"] ?></td>
                            <td class="py-3 px-6 text-left text-green-500"><?= $task["task_status"] ?></td>
                            <td class="py-3 px-6 text-left">
                                <!--<button class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">View
                                </button>-->
                                <form method="POST" action="/cancel-task-admin">
                                    <input type="hidden" name="id" value="<?= $task['task_id'] ?>">
                                    <button  class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">
                                        <a>Cancel</a>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

<?php require base_path('views/partials/footer.php');