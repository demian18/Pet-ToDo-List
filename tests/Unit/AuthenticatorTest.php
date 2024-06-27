<?php

class DatabaseMock {
    public function query($sql, $params) {
        if ($params['email'] === 'admin@test.com') {
            return [
                'email' => 'admin@test.com',
                'password' => password_hash('testadmin', PASSWORD_DEFAULT)
            ];
        }
        return null;
    }
}

class Authenticator {
    protected $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function attempt($email, $password) {
        $user = $this->database->query('select * from users where email = :email', [
            'email' => $email
        ]);

        if ($user && password_verify($password, $user['password'])) {
            return true;
        }

        return false;
    }

}

test('method attempt for login', function () {
    $databaseMock = new DatabaseMock();
    $auth = new Authenticator($databaseMock);

    $result = $auth->attempt("admin@test.com", "testadmin");
    expect($result)->toBeTrue();
});

