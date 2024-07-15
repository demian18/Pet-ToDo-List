document.addEventListener('DOMContentLoaded', function () {
    const button = document.getElementById('user-menu-button');

    if (button) {
        button.addEventListener('click', function () {
            window.location.href = '/profile';
        });
    }
});

document.querySelectorAll('.perform-btn').forEach(button => {
    button.addEventListener('click', function() {
        const taskId = this.getAttribute('data-task-id');

        fetch('/update-task-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: taskId }),
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const statusElement = document.querySelector(`.task-status[data-task-id="${taskId}"]`);
                    if (statusElement) {
                        statusElement.textContent = data.newStatus;
                    }
                    console.log('ID received:', data.id);
                } else {
                    console.error('Error:', data.message);
                }
            })
            .catch((error) => {
                console.error('Error:', error);
            });
    });
});

