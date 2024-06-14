<header class="bg-white shadow">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">
            <?php if (!$_SESSION)  : ?>
                Hello Guest! In theory, you shouldn't see anything here, it will be corrected in the future.
            <?php elseif ($_SESSION['user']['email']) : ?>
            Hello <?= $_SESSION['user']['email'] ?>! Welcome to your Task board</h1>
        <?php endif; ?>
    </div>
</header>