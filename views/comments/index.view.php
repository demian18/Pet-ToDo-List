<?php require base_path('views/partials/head.php') ?>

<?php require base_path('views/partials/nav.php') ?>

<?php require base_path('views/partials/banner.php') ?>
    <main>
        <div class="mx-auto max-w-7xl bg-white py-6 sm:px-6 lg:px-8 mt-10">

            <h1 class="text-2xl font-bold mb-4">Comments on the task:</h1>
            <?php foreach ($comments as $comment) : ?>
                <div class="bg-white rounded-lg shadow-md p-4 mt-6">
                    <div class="flex items-start border-b border-gray-200 pb-4 mb-4">
                        <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center mr-4">
                            <span class="text-lg font-semibold text-gray-600">A</span>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-500 mb-1">Comment by <?= $comment['creator_id'] ?> </p>
                            <p class="text-base font-semibold text-gray-800">Task:
                                <a href="#" class="text-blue-500 hover:underline">Task #</a></p>

                            <div class="flex-1">
                                <p class="text-sm text-gray-500 mt-3">
                                    <?= $comment['comment'] ?>
                                </p>
                            </div>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="text-gray-500 text-sm"><?= $comment['created_at'] ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <form method="POST" action="/close-notice">
                <div class="flex flex-col items-end">
                    <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-1">
                        <div class="mt-2 flex items-center">
                            <input type="hidden" name="task_id" value="<?= htmlspecialchars($taskId) ?>">
                            <button type="submit"
                                    class="ml-4 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Close the notice
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>
    <div class="mx-auto max-w-7xl bg-white py-6 sm:px-6 lg:px-8 mt-10">
        <form method="POST" action="/create-comment">
            <div class="space-y-12">
                <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-1">
                    <div class="sm:col-span-4">
                        <label for="comment"
                               class="block text-sm font-medium leading-6 text-gray-900">Add a
                            comment</label>

                        <div class="mt-2 flex items-center">
                            <input type="text" name="comment" id="comment" required
                                   class="block w-full flex-grow rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            <input type="hidden" name="task_id" value="<?= htmlspecialchars($taskId) ?>">
                            <input type="hidden" name="not_id" value="<?= htmlspecialchars($notId) ?>">
                            <button type="submit"
                                    class="ml-4 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Write
                            </button>
                        </div>
                        <?php if (isset($errors['comment'])) : ?>
                            <p class="text-red-500 text-xs mt-2"><?= htmlspecialchars($errors['comment']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </form>
    </div>

<?php require base_path('views/partials/footer.php');