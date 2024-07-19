document.addEventListener('DOMContentLoaded', function () {
    const button = document.getElementById('user-menu-button');

    if (button) {
        button.addEventListener('click', function () {
            window.location.href = '/profile';
        });
    }
});

document.querySelectorAll('.perform-btn, .help-btn').forEach(button => {
    button.addEventListener('click', function () {
        const taskId = this.getAttribute('data-task-id');
        const action = this.classList.contains('perform-btn') ? 'perform' : 'help';

        sendPostRequest(taskId, action, this);
    });
});

function sendPostRequest(taskId, action, buttonElement) {
    let url = '';

    if (action === 'perform') {
        url = '/update-task-status';
    } else if (action === 'help') {
        url = '/help-task';
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
                const statusElement = document.querySelector(`.task-status[data-task-id="${taskId}"]`);
                if (statusElement) {
                    statusElement.textContent = data.newStatus;
                }
                if (buttonElement) {
                    buttonElement.classList.remove('bg-green-500');
                    buttonElement.classList.add('bg-gray-500');
                    buttonElement.disabled = true;
                    buttonElement.textContent = 'Performed';
                }
            } else if (data.message === 'help') {
                if (buttonElement) {
                    buttonElement.classList.remove('bg-yellow-500');
                    buttonElement.classList.add('bg-red-500');
                }
            } else {
                console.error('Error:', data.message);
            }

        })
        .catch((error) => {
            console.error('Error:', error);
        });
}

document.querySelectorAll('.filter-btn').forEach(button => {
    button.addEventListener('click', function () {
        const status = this.getAttribute('data-status');

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
    });
});

// ajax cron
function fetchNotifications(userId) {
    fetch('/get-notifications', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => response.json())
        .then(data => {
            const notificationCountElement = document.getElementById('notification-count');
            console.log(data)
            const creator_id = data['0']['creator_id'];
            const type = data['0']['type'];
            if (userId == creator_id) { // Admin check
                if (type == "help") {
                    if (data.length > 0) {
                        notificationCountElement.textContent = data.length;
                        notificationCountElement.style.display = 'block';
                    } else {
                        notificationCountElement.style.display = 'none';
                    }
                }
            }
        })
        .catch(error => console.error('Error fetching notifications:', error));
}
const userId = document.querySelector('[data-user-id]').getAttribute('data-user-id');
setInterval(() => fetchNotifications(userId), 300000);
fetchNotifications(userId);