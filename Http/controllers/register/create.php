<?php

use Core\Session;

view('/register/create.view.php', [
    'errors' => Session::get('errors')
]);