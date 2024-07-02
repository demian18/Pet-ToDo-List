<?php
use Core\Validator;

class UserDatabaseMock
{
    private $users = [];

    public function addUser($sql, $params = [])
    {
        if (strpos($sql, 'INSERT INTO users') !== false) {
            $this->users[] = [
                'email' => $params['email'],
                'password' => $params['password'],
            ];
        }
    }

    public function findUserByEmail($sql, $params = [])
    {
        if (strpos($sql, 'SELECT * FROM users') !== false) {
            foreach ($this->users as $user) {
                if ($user['email'] == $params['email']) {
                    return $user;
                }
            }
        }
        return false;
    }

    public function clear()
    {
        $this->users = [];
    }
}

class ValidationMock
{
    public function validate($email, $password)
    {
        return Validator::email($email) && Validator::string($password, 5, 25);
    }
}

class SessionMock
{
    public function storeSession($email)
    {
        $_SESSION['user'] = [
            'email' => $email,
        ];
    }

    public function findSession()
    {
        return $_SESSION['user']['email'] ?? null;
    }

    public function clearSession()
    {
        session_unset();
        session_destroy();
    }
}


beforeEach(function () {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
});

afterEach(function () {
    $db = new UserDatabaseMock();
    $db->clear();
    $session = new SessionMock();
    $session->clearSession();
});

test('Validation should pass with valid data', function () {
    $email = "test@example.com";
    $password = "password";

    $validator = new ValidationMock();
    $result = $validator->validate($email, $password);

    expect($result)->toBeTrue();
});

test('Validation should fail with invalid data', function () {
    $email = "invalid-email";
    $password = "123";

    $validator = new ValidationMock();
    $result = $validator->validate($email, $password);

    expect($result)->toBeFalse();
});

test('User should not exist initially', function () {
    $email = "test@example.com";

    $db = new UserDatabaseMock();
    $result = $db->findUserByEmail('SELECT * FROM users WHERE email = :email', [
        'email' => $email
    ]);

    expect($result)->toBeFalse();
});

test('Should add user to the database', function () {
    $email = "test@example.com";
    $password = "password";

    $db = new UserDatabaseMock();
    $db->addUser('INSERT INTO users(email, password) VALUES(:email, :password)', [
        'email' => $email,
        'password' => password_hash($password, PASSWORD_BCRYPT),
    ]);

    $result = $db->findUserByEmail('SELECT * FROM users WHERE email = :email', [
        'email' => $email
    ]);
    expect($result)->toBeTruthy();

});

test('Should create session after registration', function () {
    $email = "test@example.com";
    $password = "password";

    $db = new UserDatabaseMock();
    $session = new SessionMock();
    $validator = new ValidationMock();

    $isValid = $validator->validate($email, $password);

    if ($isValid) {
        $db->addUser('INSERT INTO users(email, password) VALUES(:email, :password)', [
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT),
        ]);

        $session->storeSession($email);
    }

    $sessionEmail = $session->findSession();
    expect($sessionEmail)->toBe($email);
});