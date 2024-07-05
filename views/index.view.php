<?php require('partials/head.php') ?>

<?php require('partials/nav.php') ?>

<?php require('partials/banner.php') ?>

    <main>
        <?php if (empty($_SESSION)): ?>
            <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
                <p>Oops, to view the page, you need to log in...</p>
            </div>
        <?php else : ?>
            <?php if ($role == 2) : ?>
                <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
                    <!-- Your content -->
                    <div class="max-w-sm mx-auto">
                        <div class="bg-white shadow-lg rounded-lg p-6">
                            <h2 class="text-xl font-semibold mb-4">To-Do List</h2>
                            <div class="border-t border-gray-200 mt-4">
                                <ul class="divide-y divide-gray-200">
                                    <?php if (isset($tasks)): ?>
                                        <?php foreach ($tasks as $task) : ?>
                                            <li class="py-2 flex justify-between items-center">
                                                <a href="/edit-task?id=<?= $task['id'] ?>">
                                                    <span><?= htmlspecialchars($task['title']) ?></span>
                                                </a>
                                                <form method="POST" action="/delete-task" class="inline">
                                                    <!--<input type="checkbox" class="form-checkbox h-5 w-5 text-indigo-600">-->
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="id" value="<?= $task['id'] ?>">
                                                    <button type="submit" class="text-red-600 hover:text-red-800">Delete
                                                    </button>
                                                </form>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <p>Oops, there are no tasks yet...</p>
                                    <?php endif; ?>
                                </ul>
                            </div>
                            <div class="border-b border-gray-900/10 mt-6"></div>
                            <form method="POST" action="/create-task">
                                <div class="space-y-12">
                                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-1">
                                        <div class="sm:col-span-4">
                                            <label for="title"
                                                   class="block text-sm font-medium leading-6 text-gray-900">Add a
                                                task</label>

                                            <div class="mt-2 flex items-center">
                                                <input type="text" name="title" id="title" required
                                                       class="block w-full flex-grow rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                                <button type="submit"
                                                        class="ml-4 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                                    Save
                                                </button>
                                            </div>
                                            <?php if (isset($errors['title'])) : ?>
                                                <p class="text-red-500 text-xs mt-2"><?= htmlspecialchars($errors['title']) ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php if (isset($users)): ?>
                                        <div class="sm:col-span-4">
                                            <label for="assignee"
                                                   class="block text-sm font-medium leading-6 text-gray-900">Assign
                                                to</label>
                                            <div class="mt-2">
                                                <select id="assignee" name="assignee" required
                                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                                    <option value="" disabled selected>Select a worker</option>
                                                    <?php foreach ($users as $user) : ?>
                                                        <option value="<?= $user['id'] ?>"><?= $user['email'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <?php if (isset($errors['assignee'])) : ?>
                                                <p class="text-red-500 text-xs mt-2"><?= htmlspecialchars($errors['assignee']) ?></p>
                                            <?php endif; ?>
                                        </div>
                                    <?php else : ?>
                                        <p>Oops, there are no tasks yet...</p>
                                    <?php endif; ?>
                                </div>
                            </form>

                            <!--<div class="mt-4 flex">
                                <input type="text" placeholder="Add a new task" class="flex-1 px-4 py-2 border rounded-l-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <button class="bg-indigo-600 text-white px-4 py-2 rounded-r-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">Add</button>
                            </div>-->
                        </div>
                    </div>
                </div>
            <?php else : ?>
                <div class="mx-auto max-w-7xl bg-white py-6 sm:px-6 lg:px-8 mt-10">

                    <div class="bg-gray-800 text-white px-4 py-3">
                        <h2 class="text-xl font-semibold">My tasks</h2>
                    </div>

                    <div class="px-6 py-4">
                        <h3 class="text-lg font-semibold">Filters:</h3>
                        <div class="mt-2 space-x-4">
                            <button class="bg-blue-500 text-white px-4 py-2 rounded-md">All</button>
                            <button class="bg-blue-500 text-white px-4 py-2 rounded-md">Completed</button>
                            <button class="bg-blue-500 text-white px-4 py-2 rounded-md">Unfulfilled</button>
                            <button class="bg-blue-500 text-white px-4 py-2 rounded-md">Help</button>
                        </div>
                    </div>
                    <?php if (isset($tasks)): ?>
                        <?php foreach ($tasks as $task) : ?>
                            <div class="px-6 py-4">
                                <table class="min-w-full bg-white">
                                    <thead>
                                    <tr>
                                        <th class="py-2 text-left">Task title</th>
                                        <th class="py-2 text-left">Period of execution</th>
                                        <th class="py-2 text-left">Status</th>
                                        <th class="py-2 text-left">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td class="py-2"><?= $task['title'] ?></td>
                                        <td class="py-2">2024-06-01</td>
                                        <td class="py-2"><?= $task['status'] ?></td>
                                        <td class="py-2 space-x-2">
                                            <button class="bg-green-500 text-white px-2 py-1 rounded-md">To perform
                                            </button>
                                            <button class="bg-yellow-500 text-white px-2 py-1 rounded-md">Help</button>
                                            <button class="bg-blue-500 text-white px-2 py-1 rounded-md">Comment</button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p>Oops, there are no tasks yet...</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </main>
<?php require('partials/footer.php');