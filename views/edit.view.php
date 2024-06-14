<?php require('partials/head.php') ?>

<?php require('partials/nav.php') ?>

<?php require('partials/banner.php') ?>

    <main>
        <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
            <!-- Your content -->
            <div class="max-w-sm mx-auto">
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <h2 class="text-xl font-semibold mb-4">Update Task</h2>
                    <div class="border-b border-gray-900/10 mt-6"></div>
                    <form method="POST" action="/update-task">
                        <input type="hidden" name="_method" value="PATCH">
                        <input type="hidden" name="id" value="<?= $task['id'] ?>">
                        <div class="space-y-12">
                            <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-1">
                                <div class="sm:col-span-4">
                                    <label for="title"
                                           class="block text-sm font-medium leading-6 text-gray-900">Update Task</label>
                                    <div class="mt-2 flex items-center">
                                        <input type="text" name="title" id="title" required
                                               class="block w-full flex-grow rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                               value="<?= htmlspecialchars($task['title'], ENT_QUOTES, 'UTF-8') ?>">
                                        <button type="submit"
                                                class="ml-4 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                            Update
                                        </button>
                                    </div>
                                    <?php if (isset($errors['title'])) : ?>
                                        <p class="text-red-500 text-xs mt-2"><?= $errors['title'] ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </main>
<?php require('partials/footer.php');