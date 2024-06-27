<?php

class DatabaseMock_3
{
    private $task = [];

    public function query($sql, $params = [])
    {
        if (strpos($sql, 'INSERT INTO todo') !== false) {
            $this->task [] = [
                'title' => $params['title'],
                'id' => $params['id']
            ];
        }
    }

    public function update($sql, $params = [])
    {
        if (strpos($sql, 'UPDATE todo SET') !== false) {
            foreach ($this->task as &$task) {
                if ($task['id'] == $params['id']) {
                    $task['title'] = $params['title'];
                    return $task;
                }
            }
        }
        return false;
    }
}

test('Update task', function () {
    $db = new DatabaseMock_3();
    $title = 'Test create check';
    $id = 1;

    $db->query('INSERT INTO todo (title, id) VALUES (:title, :id)', [
        'title' => $title,
        'id' => $id
    ]);
    $newTitle = 'Change';
    $result = $db->update('UPDATE todo SET title = :title WHERE id = :id', [
        'title' => $newTitle,
        'id' => $id
    ]);

    expect($result['title'])->toEqual($newTitle);
});