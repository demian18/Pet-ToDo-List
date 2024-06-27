<?php

class DatabaseMock_2
{
    private $tasks = [];

    public function query($sql, $params = [])
    {
        if (strpos($sql, 'INSERT INTO todo') !== false) {
            $this->tasks[] = [
                'title' => $params['title'],
                'id' => $params['id'],
            ];
        }
    }

    public function delete($sql, $params = [])
    {
        if (strpos($sql, 'DELETE FROM todo') !== false) {
            foreach ($this->tasks as $index => $task) {
                if ($task['id'] == $params['id']) {
                    unset($this->tasks[$index]);
                    return true;
                }
            }
        }
        return false;
    }

    public function getTasks()
    {
        return array_values($this->tasks);
    }
}

test('Delete Task', function () {
    $db = new DatabaseMock_2();

    $db->query('INSERT INTO todo (title, id) VALUES (:title, :id)', [
        'title' => 'Task 1',
        'id' => 1
    ]);
    $db->query('INSERT INTO todo (title, id) VALUES (:title, :id)', [
        'title' => 'Task 2',
        'id' => 2
    ]);
    $db->query('INSERT INTO todo (title, id) VALUES (:title, :id)', [
        'title' => 'Task 3',
        'id' => 3
    ]);

    $tasks = $db->getTasks();
    expect(count($tasks))->toEqual(3);

    $found = false;
    foreach ($tasks as $task) {
        if ($task['id'] === 2) {
            $found = true;
            break;
        }
    }
    expect($found)->toBeTrue();

    $result = $db->delete('DELETE FROM todo WHERE id = :id', [
        'id' => 2
    ]);
    expect($result)->toBeTrue();

    $tasks = $db->getTasks();
    foreach ($tasks as $task) {
        expect($task['id'])->not->toEqual(2);
    }

    expect(count($tasks))->toEqual(2);
    $ids = array_map(fn($task) => $task['id'], $tasks);
    expect($ids)->toEqual([1, 3]);
});
