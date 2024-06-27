<?php

class DatabaseMock_1
{
    private $task = [];

    public function query($sql, $params = [])
    {
        if (strpos($sql, 'INSERT INTO todo') !== false) {
            $this->task[] = [
                'title' => $params['title'],
                'user_id' => $params['user_id']
            ];
        }
    }

    public function find($sql, $params = [])
    {
        if (strpos($sql, 'SELECT * FROM todo') !== false) {
            foreach ($this->task as $task) {
                if ($task['user_id'] == $params['user_id']){
                    return $task;
                }
            }
        }
        return null;
    }
}

test('Create task', function () {
    $db = new DatabaseMock_1();
    $title = 'Test create check';
    $user_id = 1;

    $db->query('INSERT INTO todo (title, user_id) VALUES (:title, :user_id)', [
        'title' => $title,
        'user_id' => $user_id
    ]);

    $result = $db->find('SELECT * FROM todo WHERE user_id = :user_id', [
        'user_id' => $user_id
    ]);

    expect($result['title'])->toEqual($title);
});