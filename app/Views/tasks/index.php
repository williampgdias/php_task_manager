<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Login Form -->
<div id="loginSection" class="row justify-content-center mt-5">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center mb-4">Login</h4>
                <div id="loginError" class="alert alert-danger" style="display:none;"></div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" id="loginEmail">
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" id="loginPassword">
                </div>
                <button class="btn btn-primary w-100" id="loginBtn">Login</button>
                <p class="text-center mt-3">
                    <a href="#" id="showRegister">Create account</a>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Register Form -->
<div id="registerSection" class="row justify-content-center mt-5" style="display:none;">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center mb-4">Register</h4>
                <div id="registerError" class="alert alert-danger" style="display:none;"></div>
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control" id="registerName">
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" id="registerEmail">
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" id="registerPassword">
                </div>
                <button class="btn btn-success w-100" id="registerBtn">Register</button>
                <p class="text-center mt-3">
                    <a href="#" id="showLogin">Back to login</a>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Task Section -->
<div id="tasksSection" style="display:none;">
    <div class="d-flex justify-between align-items-center mb-4 gap-3">
        <h3>My Tasks</h3>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#taskModal" id="newTaskBtn">
            + New Task
        </button>
    </div>

    <div class="row" id="tasksList">
        <!-- Tasks loaded here by jQuery -->
    </div>
</div>

<!-- Task Modal -->
<div class="modal fade" id="taskModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">New Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="taskId">
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" class="form-control" id="taskTitle">
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" id="taskDescription" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" id="taskStatus">
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveTaskBtn">Save</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
const API_URL = '/api';
let token = localStorage.getItem('token');

// Check if logged in
if (token) {
    showTasks();
}

// Toggle forms
$('#showRegister').click(function(e) {
    e.preventDefault();
    $('#loginSection').hide();
    $('#registerSection').show();
});

$('#showLogin').click(function(e) {
    e.preventDefault();
    $('#registerSection').hide();
    $('#loginSection').show();
});

// Login
$('#loginBtn').click(function() {
    $.ajax({
        url: API_URL + '/login',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            email: $('#loginEmail').val(),
            password: $('#loginPassword').val()
        }),
        success: function(res) {
            token = res.data.api_token;
            localStorage.setItem('token', token);
            showTasks();
        },
        error: function(xhr) {
            $('#loginError').text('Invalid email or password.').show();
        }
    });
});

// Register
$('#registerBtn').click(function() {
    $.ajax({
        url: API_URL + '/register',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            name: $('#registerName').val(),
            email: $('#registerEmail').val(),
            password: $('#registerPassword').val()
        }),
        success: function(res) {
            $('#registerSection').hide();
            $('#loginSection').show();
            alert('Account created! Please login.');
        },
        error: function(xhr) {
            const errors = xhr.responseJSON?.errors;
            $('#registerError').text(errors ? Object.values(errors).join(', ') :
                'Registration failed.').show();
        }
    });
});

// Logout
$('#logoutBtn').click(function() {
    token = null;
    localStorage.removeItem('token');
    $('#tasksSection').hide();
    $('#logoutBtn').hide();
    $('#loginSection').show();
});

// Show tasks section
function showTasks() {
    $('#loginSection').hide();
    $('#registerSection').hide();
    $('#tasksSection').show();
    $('#logoutBtn').show();
    loadTasks();
}

// Load tasks
function loadTasks() {
    $.ajax({
        url: API_URL + '/tasks',
        method: 'GET',
        headers: {
            'Authorization': 'Bearer ' + token
        },
        success: function(res) {
            let html = '';
            if (res.data.length === 0) {
                html =
                    '<div class="col-12 text-center text-muted"><p>No tasks yet. Create your first one!</p></div>';
            }
            res.data.forEach(function(task) {
                html += `
                    <div class="col-md-4 mb-3">
                        <div class="card task-card status-${task.status}">
                            <div class="card-body">
                                <h5 class="card-title">${task.title}</h5>
                                <p class="card-text text-muted">${task.description || 'No description'}</p>
                                <span class="badge bg-${getStatusColor(task.status)}">${formatStatus(task.status)}</span>
                                <div class="mt-3">
                                    <button class="btn btn-sm btn-outline-primary edit-task" data-id="${task.id}" data-title="${task.title}" data-description="${task.description || ''}" data-status="${task.status}">Edit</button>
                                    <button class="btn btn-sm btn-outline-danger delete-task" data-id="${task.id}">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            $('#tasksList').html(html);
        }
    });
}

// New task button
$('#newTaskBtn').click(function() {
    $('#modalTitle').text('New Task');
    $('#taskId').val('');
    $('#taskTitle').val('');
    $('#taskDescription').val('');
    $('#taskStatus').val('pending');
});

// Save Task
$('#saveTaskBtn').click(function() {
    const id = $('#taskId').val();
    const data = {
        title: $('#taskTitle').val(),
        description: $('#taskDescription').val(),
        status: $('#taskStatus').val()
    };

    const method = id ? 'PUT' : 'POST';
    const url = id ? API_URL + '/tasks/' + id : API_URL + '/tasks';

    $.ajax({
        url: url,
        method: method,
        contentType: 'application/json',
        headers: {
            'Authorization': 'Bearer ' + token
        },
        data: JSON.stringify(data),
        success: function(res) {
            $('#taskModal').modal('hide');
            loadTasks();
        }
    });
});

// Edit Task
$(document).on('click', '.edit-task', function() {
    $('#modalTitle').text('Edit Task');
    $('#taskId').val($(this).data('id'));
    $('#taskTitle').val($(this).data('title'));
    $('#taskDescription').val($(this).data('description'));
    $('#taskStatus').val($(this).data('status'));
    $('#taskModal').modal('show');
});

// Delete task
$(document).on('click', '.delete-task', function() {
    if (confirm('Delete this task?')) {
        const id = $(this).data('id');
        $.ajax({
            url: API_URL + '/tasks/' + id,
            method: 'DELETE',
            headers: {
                'Authorization': 'Bearer ' + token
            },
            success: function() {
                loadTasks();
            }
        });
    }
});

// Helpers
function getStatusColor(status) {
    const colors = {
        pending: 'warning',
        in_progress: 'primary',
        completed: 'success'
    };
    return colors[status] || 'secondary';
}

function formatStatus(status) {
    return status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
}
</script>
<?= $this->endSection() ?>