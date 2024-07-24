<tr>
    <td class="py-2"><?= $task['title'] ?></td>
    <td class="py-2"><?= $task['task_id'] ?></td>
    <td class="py-2 task-status"
        data-task-id="<?= $task['task_id'] ?>"><?= $task['status_name'] ?></td>
    <td class="py-2 space-x-2">
        <button class="<?= $task['status_id'] == 2 ? 'bg-green-500' : 'bg-gray-500' ?> text-white px-2 py-1 rounded-md perform-btn"
                data-task-id="<?= $task['task_id'] ?>"
            <?= $task['status_id'] != 2 ? 'disabled' : '' ?>>
            <?= $task['status_id'] == 2 ? 'To perform' : 'Performed' ?>
        </button>
        <button class="<?= $task['status_id'] == 2 ? 'bg-yellow-500' : ($task['status_id'] == 4 ? 'bg-red-500' : 'bg-gray-500') ?> text-white px-2 py-1 rounded-md help-btn"
                data-task-id="<?= $task['task_id'] ?>"
            <?= $task['status_id'] != 2 ? 'disabled' : '' ?>>
            Help
        </button>
    </td>
</tr>