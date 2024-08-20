document.addEventListener('DOMContentLoaded', function () {
    const button = document.getElementById('user-menu-button');

    if (button) {
        button.addEventListener('click', function () {
            window.location.href = '/profile';
        });
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const button = document.getElementById('user-notifications-button');

    if (button) {
        button.addEventListener('click', function () {
            window.location.href = '/notifications';
        });
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.to-write-btn');

    buttons.forEach(function (button) {
        button.addEventListener('click', function () {
            const taskId = this.getAttribute('data-task-id');
            window.location.href = `/task-comments?id=${taskId}`;
        });
    });
});

function sendPostRequest(taskId, action, buttonElement) {
    let url = '';

    if (action === 'perform') {
        url = '/update-task-status';
    } else if (action === 'help') {
        url = '/help-task';
    } else if (action === 'cancel') {
        url = '/cancel-task';
    }

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({id: taskId}),
    })
        .then(response => response.json())
        .then(data => {
            if (data.message === 'perform') {
                handlePerformResponse(data, buttonElement);
            } else if (data.message === 'help') {
                handleHelpResponse(data, buttonElement);
            } else if (data.message === 'cancel') {
                handleCancelResponse(data, buttonElement);
            } else {
                console.error('Error:', data.message);
            }
        })
        .catch((error) => {
            console.error('Error:', error);
        });
}

function handlePerformResponse(data, buttonElement) {
    const statusElement = document.querySelector(`.task-status[data-task-id="${data.id}"]`);
    const helpButton = document.querySelector(`.help-btn[data-task-id="${data.id}"]`);
    if (statusElement) {
        statusElement.textContent = data.newStatus;
    }
    if (buttonElement) {
        buttonElement.classList.remove('bg-green-500');
        buttonElement.classList.add('bg-gray-500');
        buttonElement.disabled = true;
        buttonElement.textContent = 'Performed';
    }
    if (helpButton) {
        helpButton.classList.remove('bg-yellow-500');
        helpButton.classList.add('bg-gray-500');
        helpButton.disabled = true;
    }
}

function handleHelpResponse(data, buttonElement) {
    const statusElement = document.querySelector(`.task-status[data-task-id="${data.id}"]`);
    if (statusElement) {
        statusElement.textContent = data.newStatus;
    }
    if (buttonElement) {
        buttonElement.classList.remove('bg-yellow-500');
        buttonElement.classList.add('bg-red-500');
        buttonElement.disabled = true;
    }
}

function handleCancelResponse(data, buttonElement) {
    const statusElement = document.querySelector(`.task-status[data-task-id="${data.id}"]`);
    if (statusElement) {
        statusElement.textContent = data.newStatus;
    }
    if (buttonElement) {
        buttonElement.classList.remove('bg-blue-500');
        buttonElement.classList.add('bg-red-500');
        buttonElement.disabled = true;
        buttonElement.textContent = 'Cancelled';
    }
}

document.addEventListener('click', function(event) {
    if (event.target.classList.contains('filter-btn')) {
        handleFilterClick(event);
    } else if (event.target.classList.contains('perform-btn') ||
        event.target.classList.contains('help-btn') ||
        event.target.classList.contains('cancel-btn')) {
        handleActionClick(event);
    }
});

function handleFilterClick(event) {
    const status = event.target.getAttribute('data-status');

    fetch('/filter-tasks', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({status: status}),
    })
        .then(response => response.json())
        .then(data => {
            const tasksBody = document.querySelector('#tasks-body');
            tasksBody.innerHTML = data.tasksHtml;

        })
        .catch((error) => {
            console.error('Error:', error);
        });
}

function handleActionClick(event) {
    const button = event.target;
    const taskId = button.getAttribute('data-task-id');
    let action;

    if (button.classList.contains('perform-btn')) {
        action = 'perform';
    } else if (button.classList.contains('help-btn')) {
        action = 'help';
    } else if (button.classList.contains('cancel-btn')) {
        action = 'cancel';
    }

    sendPostRequest(taskId, action, button);
}

// ajax cron
function fetchNotifications() {
    fetch('/get-notifications', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => response.json())
        .then(data => {
            const notificationCountElement = document.getElementById('notification-count');
            const count = data.count;

            if (count > 0) {
                notificationCountElement.textContent = count;
                notificationCountElement.style.display = 'block';
            } else {
                notificationCountElement.style.display = 'none';
            }

        })
        .catch(error => console.error('Error fetching notifications:', error));
}

setInterval(fetchNotifications, 300000);
fetchNotifications();