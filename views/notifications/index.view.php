<?php require base_path('views/partials/head.php') ?>

<?php require base_path('views/partials/nav.php') ?>

<?php require base_path('views/partials/banner.php') ?>
    <main>
        <div class="mx-auto max-w-7xl bg-white py-6 sm:px-6 lg:px-8 mt-10">

            <h1 class="text-2xl font-bold mb-4">Your Notifications</h1>
            <?php foreach ($notifications as $not) : ?>
                <div class="bg-white rounded-lg shadow-md p-4 mt-6">
                    <div class="flex items-start border-b border-gray-200 pb-4 mb-4">
                        <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center mr-4">
                            <span class="text-lg font-semibold text-gray-600">A</span>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-500 mb-1">Notification by <?= $not['type'] ?></p>
                            <p class="text-base font-semibold text-gray-800">Task: <a href="#"
                                                                                      class="text-blue-500 hover:underline">Task
                                    #<?= $not['task_id'] ?></a></p>
                            <p class="text-sm text-gray-600">Worker: <span
                                        class="font-medium"><?= $not['email'] ?></span></p>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="text-gray-500 text-sm"><?= $not['created_at'] ?></span>
                            <button class="bg-blue-500 text-white px-4 py-2 rounded-md mt-auto to-write-btn"
                            data-task-id="<?= $not['task_id'] ?>">To Write</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

<?php require base_path('views/partials/footer.php');