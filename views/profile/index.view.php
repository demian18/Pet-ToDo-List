<?php require base_path('views/partials/head.php') ?>

<?php require base_path('views/partials/nav.php') ?>

<?php require base_path('views/partials/banner.php') ?>

    <main>
        <div class="mx-auto max-w-7xl bg-white py-6 sm:px-6 lg:px-8 mt-10">

            <div class="bg-gray-800 text-white px-4 py-3">
                <h2 class="text-xl font-semibold">User Profile</h2>
            </div>


            <div class="px-6 py-4">
                <h3 class="text-lg font-semibold">Username</h3>
                <p class="text-gray-600">John Doe</p>
            </div>


            <div class="px-6 py-4">
                <img class="h-24 w-24 rounded-full mx-auto" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="Profile Picture">
            </div>


            <div class="px-6 py-4">
                <h3 class="text-lg font-semibold">Statistics</h3>
                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div>
                        <p class="text-gray-600">Time in the company</p>
                        <p class="text-gray-800 font-semibold">2 years</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Tasks completed</p>
                        <p class="text-gray-800 font-semibold">100</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Tasks canceled</p>
                        <p class="text-gray-800 font-semibold">5</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Location</p>
                        <p class="text-gray-800 font-semibold">New York, USA</p>
                    </div>
                </div>
            </div>
        </div>

    </main>
<?php require base_path('views/partials/footer.php');