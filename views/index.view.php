<?php require('partials/head.php') ?>

<?php require('partials/nav.php') ?>

<?php require('partials/banner.php') ?>

    <main>
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
                                           class="block text-sm font-medium leading-6 text-gray-900">Add a task</label>
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
                        </div>
                    </form>

                    <!--<div class="mt-4 flex">
                        <input type="text" placeholder="Add a new task" class="flex-1 px-4 py-2 border rounded-l-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <button class="bg-indigo-600 text-white px-4 py-2 rounded-r-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">Add</button>
                    </div>-->
                </div>
            </div>

        </div>
    </main>
<?php require('partials/footer.php');