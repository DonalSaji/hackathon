@extends('backend.body.master')
@section('title', 'All Todos')

@section('content')

    <style>
        @media(max-width:576px) {
            .gotoDashboardbtn1 {
                display: none;
            }

        }

        .striking {
            text-decoration: line-through;
            text-decoration-thickness: 2px;
            text-decoration-color: black;
        }
    </style>

    <div class="row d-flex justify-content-center align-items-center">
        <div class="col-6">
            <div class="page-title-box">
                <h4 class="page-title">All Todo Lists</h4>
            </div>
        </div>
        <div class="col-6 text-right" style="z-index:2;">
            <button type="button" class="btn btn-primary float-end" onclick="window.location.href='{{ route('dashboard') }}'">
                <span class="gotoDashboardbtn1">Go to Dashboard</span>
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-0">
                <div class="card-header d-flex justify-content-between align-items-center ps-2 mb-0">
                    <h4 class="mb-0" style="padding:9px 0;">All Latest Todos</h4>
                    @can('add.todo')
                        <a href="#" data-bs-toggle="modal" data-bs-target="#taskModal">
                            <i class="mdi mdi-plus fs-2"></i>
                        </a>
                    @endcan
                </div>
                <div class="card-body mt-0 p-0">
                    <ul id="todo-list" class="ps-0 mt-0 todo-list">
                        @foreach ($todos as $todo)
                            <li class="d-flex justify-content-between align-items-start rounded px-3 py-2 mb-2">

                                <form action="{{ route('todos.complete', $todo) }}"
                                    class="d-flex align-items-center gap-3 mt-2" method="POST">
                                    @csrf

                                    <div class="form-check m-0 px-2">

                                        <input type="checkbox" class="form-check-input" onchange="this.form.submit()"
                                            {{ $todo->completed ? 'checked' : '' }}>

                                        <span class="flex-grow-1 text-wrap text-break">{{ $todo->name }}</span>

                                        <span
                                            class="d-block text-muted">Due: {{ $todo->due_date ? \Carbon\Carbon::parse($todo->due_date)->endOfDay()->format('F d Y') : 'No Due Date' }}</span>
                                    </div>
                                </form>

                                <div class="d-flex align-items-center justify-content-center">
                                    @can('edit.todo')
                                    @if(!$todo->due_date || \Carbon\Carbon::parse($todo->due_date)->endOfDay()->isFuture())
                                    <a href="#editTaskModal" class="editTodo ms-2" data-bs-toggle="modal"
                                        data-id="{{ $todo->id }}" data-title="{{ $todo->name }}"
                                        data-due-date="{{ $todo->due_date }}"
                                        data-repeat-every="{{ $todo->recurrence->repeat_every ?? '' }}">
                                        <i class="mdi mdi-circle-edit-outline mdi-24px"></i>
                                    </a>
                                @endif
                                    @endcan
                                    @can('delete.todo')
                                        <a href="#delete-alert-modal" class="deleteTodo ms-2" data-bs-toggle="modal"
                                            data-bs-title="{{ $todo->title }}" data-id="{{ $todo->id }}"
                                            data-rec-id="{{ $todo->rec_id }}" data-bs-custom-class="danger-tooltip"
                                            data-bs-placement="top" data-bs-toggle="tooltip" title="Delete">
                                            <i class="mdi mdi-delete-circle-outline mdi-24px" style="color: #ff5b5b">
                                            </i>
                                        </a>
                                    @endcan

                                </div>
                            </li>
                        @endforeach
                    </ul>



                </div>

            </div>

            @if ($todos->hasMorePages())
                <div class="text-center mt-2">
                    <button id="load-more" class="btn btn-sm btn-primary load-more"
                        data-next-page="{{ $todos->nextPageUrl() }}" data-status="incomplete">
                        Load More
                    </button>
                </div>
            @endif
        </div>

        <div class="col-md-6">
            <div class="card mb-0">
                <div class="card-header d-flex justify-content-between align-items-center ps-2 mb-0">
                    <h4 class="mb-0" style="padding:9px 0; ">All Completed Todos</h4>
                </div>
                <div class="card-body mt-0 p-0">

                    <ul id="todo-list-completed" class="ps-0 mt-0 todo-list">
                        @foreach ($completedTodos as $todo)
                            <li class="d-flex justify-content-between align-items-start rounded px-3 py-2 mb-2">



                                <form action="{{ route('todos.incomplete', $todo) }}" method="POST"
                                    class="d-flex align-items-center gap-3 mt-2">
                                    @csrf

                                    <div class="form-check m-0 px-2">
                                        <input type="checkbox" class="form-check-input" onchange="this.form.submit()"
                                            checked>

                                        <span class="flex-grow-1 text-wrap text-break"
                                            style="text-decoration: line-through;text-decoration-thickness: 2px; 
                                        text-decoration-color: black;">
                                            {{ $todo->name }}
                                        </span>
                                        <span
                                            class="d-block text-muted">Due: {{ $todo->due_date ? \Carbon\Carbon::parse($todo->due_date)->endOfDay()->format(' 
                                            F d Y') : 'No Due Date' }}</span>
                                    </div>
                                </form>
                                {{-- Delete Option --}}
                                <div class="d-flex align-items-center justify-content-center">

                                    <a href="#delete-alert-modal" class="deleteTodo ms-2" data-bs-toggle="modal"
                                        data-bs-title="{{ $todo->title }}" data-id="{{ $todo->id }}"
                                        data-rec-id="{{ $todo->rec_id }}" data-bs-custom-class="danger-tooltip"
                                        data-bs-placement="top" data-bs-toggle="tooltip" title="Delete">
                                        <i class="mdi mdi-delete-circle-outline mdi-24px" style="color: #ff5b5b"></i>
                                    </a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            @if ($completedTodos->hasMorePages())
                <div class="text-center mt-2">
                    <button id="load-more" class="btn btn-sm btn-primary load-more"
                        data-next-page="{{ $completedTodos->nextPageUrl() }}" data-status="complete">
                        Load More
                    </button>
                </div>
            @endif

        </div>


        {{-- Delete Todo Modal --}}

        <div class="modal fade" id="delete-alert-modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Todo Alert</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete the todo <span id="delete-todo-title"></span>?
                        <input type="hidden" id="delete-todo-id">

                        <!-- The Recurrence Checkbox will be dynamically added here -->
                        <div id="recurrence-checkbox-container"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger confirmDelete">Delete</button>
                    </div>
                </div>
            </div>
        </div>


        {{-- End Delete Todo modal --}}


        {{-- Add Task Modal --}}

        <div class="modal fade" id="taskModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Todo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="taskForm" action="{{ route('todos.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="title" class="form-label">Todo Title</label>
                                <input type="text" class="form-control" id="title" name="title">
                            </div>
                            <div>
                                @error('title')
                                    <span class="text-muted mt-1">{{ $error }}</span>
                                @enderror
                            </div>


                            <div class="mb-3">
                                <label for="due_date" class="form-label">Todo Due Date</label>
                                <input type="text" class="form-control" id="due_date" name="due_date" required>
                            </div>

                            <div class="mb-3">
                                <label for="repeat_every" class="form-label">Repeat Every</label>
                                <select class="form-select" id="repeat_every" name="repeat_every">
                                    <option value="">Please Select</option>
                                    <option value="Daily">Daily</option>
                                    <option value="Weekdays">Weekdays</option>
                                    <option value="Weekly">Weekly</option>
                                    <option value="Monthly">Monthly</option>
                                    <option value="Yearly">Yearly</option>
                                    <option value="Custom">Custom</option>
                                </select>
                            </div>

                            <div class="customContainer mb-3 d-none d-flex justify-content-between align-items-center">
                                <input class="form-control" type="number" name="custom_days" min="1"
                                    value="1">
                                <span>day(s)</span>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button id="saveTodo" type="submit" class="btn btn-primary">Save Todo</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- End Add Task Modal --}}

        {{-- Edit Task Modal --}}

        <div class="modal fade" id="editTaskModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Todo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editTaskForm" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" id="edit_todo_id" name="id">
                            <div class="mb-3">
                                <label for="edit_title" class="form-label">Todo Title</label>
                                <input type="text" class="form-control" id="edit_title" name="title">
                                @error('title')
                                    <span class="text-muted mt-2">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="edit_due_date" class="form-label">Todo Due Date</label>
                                <input type="text" class="form-control" id="edit_due_date" name="due_date">
                            </div>
                            <div class="mb-3">
                                <label for="edit_repeat_every" class="form-label">Repeat Every</label>
                                <select class="form-select" id="edit_repeat_every" name="repeat_every">
                                    <option value="" disabled>Please Select</option>
                                    <option value="Daily">Daily</option>
                                    <option value="Weekdays">Weekdays</option>
                                    <option value="Weekly">Weekly</option>
                                    <option value="Monthly">Monthly</option>
                                    <option value="Yearly">Yearly</option>
                                    <option value="Custom">Custom</option>
                                </select>
                            </div>
                            <div class="customContainer mb-3 d-none d-flex justify-content-between align-items-center">
                                <input type="number" class="form-control" name="custom_days" min="1"
                                    value="1">
                                <span class="px-2">day(s)</span>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button id="saveEditTodo" type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        {{-- End Edit Task Modal --}}



        @push('custom-scripts')
            <script src="{{ asset('assets/vendor/daterangepicker/moment.min.js') }}"></script>
            <script src="{{ asset('assets/vendor/daterangepicker/daterangepicker.js') }}"></script>

            <script>
                // flatpickr for due date 
                $("#due_date").flatpickr({
                    altInput: true,
                    altFormat: "F j, Y",
                    dateFormat: "Y-m-d",
                    defaultDate: "today",
                    minDate: "today"
                });



                // To hide or show the Custom Interval for add task
                $('#repeat_every').on('change', function() {
                    console.log("Selected value:", $(this).val()); // Ensure this logs correctly
                    $('.customContainer').toggleClass('d-none', $(this).val() !== 'Custom');
                });

                $('#edit_repeat_every').on('change', function() {
                    console.log("Selected value:", $(this).val()); // Ensure this logs correctly
                    $('.customContainer').toggleClass('d-none', $(this).val() !== 'Custom');
                });

                //adding todo
                // Handle Task Form Submission (AJAX)
                $('#taskForm').on('submit', function(e) {
                    e.preventDefault(); // Prevent the default form submission

                    $('.text-danger').remove();
                    const csrfToken = document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content");

                    let formData = new FormData(this);
                    let formAction = $(this).attr('action');

                    $.ajax({
                        url: formAction,
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            "X-CSRF-Token": csrfToken,
                        },
                        success: function(response) {
                            if (response.error) {
                                // error notification
                                $.NotificationApp.send(
                                    "Error",
                                    response.error,
                                    "top-right",
                                    "rgba(0,0,0,0.2)",
                                    "error"
                                );

                                window.location.reload(); // Reload the page to reflect changes
                            } else {
                                // Success notification
                                $.NotificationApp.send(
                                    "Success",
                                    response.success,
                                    "top-right",
                                    "rgba(0,0,0,0.2)",
                                    "success"
                                );

                                $('#taskModal').modal('hide');
                                setTimeout(function() {
                                    // window.history.back(); // Redirect to the previous page
                                    window.location.reload();
                                }, 500);


                            }
                        },
                        error: function(xhr, status, error) {
                            // If validation errors occur
                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                for (let field in errors) {
                                    $(`#${field}`).after(
                                        `<span class="text-danger">${errors[field][0]}</span>`
                                    );
                                    $.NotificationApp.send(
                                        "Error",
                                        "Failed to add Todo Items",
                                        "top-right",
                                        "rgba(0,0,0,0.2)",
                                        "error",
                                    );


                                }
                            } else {
                                // General other things error notification
                                $.NotificationApp.send(
                                    "Error",
                                    "Something went wrong, Please try again",
                                    "top-right",
                                    "rgba(0,0,0,0.2)",
                                    "error",
                                );
                            }
                        }
                    });
                });

                // edit todo 

                $(document).on('click', '.editTodo', function() {
                    const todoId = $(this).data('id');
                    const todoTitle = $(this).data('title');
                    const todoDueDate = $(this).data('due-date');
                    const todoRepeatEvery = $(this).data('repeat-every');



                    $('#edit_todo_id').val(todoId);
                    $('#edit_title').val(todoTitle);
                    $('#edit_due_date').flatpickr({
                        altInput: true,
                        altFormat: "F j, Y",
                        dateFormat: "Y-m-d",
                        defaultDate: todoDueDate,
                        minDate: "today",
                    });
                    $('#edit_repeat_every').val(todoRepeatEvery);
                });

                // Handle form submission
                $('#editTaskForm').on('submit', function(e) {
                    e.preventDefault();

                    $('.text-danger').remove(); // Clear previous errors
                    const todoId = $('#edit_todo_id').val();
                    const formData = $(this).serialize();
                    const csrfToken = $('meta[name="csrf-token"]').attr('content');

                    $.ajax({
                        url: `/todos/${todoId}`,
                        type: 'PUT',
                        data: formData,
                        headers: {
                            'X-CSRF-Token': csrfToken,
                        },
                        success: function(response) {
                            if (response.success) {
                                $.NotificationApp.send(
                                    'Success',
                                    response.success,
                                    'top-right',
                                    'rgba(0,0,0,0.2)',
                                    'success'
                                );
                                $('#editTaskModal').modal('hide');
                                setTimeout(() => window.location.reload(), 1000);
                            }
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                const errors = xhr.responseJSON.errors;
                                for (let field in errors) {
                                    $(`#edit_${field}`).after(
                                        `<span class="text-danger">${errors[field][0]}</span>`
                                    );
                                }
                            } else {
                                $.NotificationApp.send(
                                    'Error',
                                    'Something went wrong. Please try again.',
                                    'top-right',
                                    'rgba(0,0,0,0.2)',
                                    'error'
                                );
                            }
                        }
                    });
                });

                // Handle Delete Todo Modal
                $(document).on('click', '.deleteTodo', function() {
                    const todoId = $(this).data('id');
                    const todoTitle = $(this).data('title');
                    const recurrenceId = $(this).data('rec-id');

                    $('#delete-todo-id').val(todoId);
                    $('#delete-todo-title').text(todoTitle);

                    if (recurrenceId) {
                        $('#recurrence-checkbox-container').html(`
                          <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="${todoId}" id="todo-delete-checkbox-${todoId}" data-rec-id="${recurrenceId}">
                         <label class="form-check-label" for="todo-delete-checkbox-${todoId}">
                         Also delete the recurring todo
                       </label>
                       </div>
                        `);
                    } else {

                        $('#recurrence-checkbox-container').html('');
                    }


                    $('#delete-alert-modal').modal('show');



                });

                // Handle Delete Confirmation

                $(document).on('click', '.confirmDelete', function() {
                    const todoId = $('#delete-todo-id').val();
                    const csrfToken = $('meta[name="csrf-token"]').attr('content');

                    $.ajax({
                        url: `/todos/${todoId}`,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-Token': csrfToken,
                        },
                        success: function(response) {
                            if (response.success) {
                                $.NotificationApp.send(
                                    'Success',
                                    response.success,
                                    'top-right',
                                    'rgba(0,0,0,0.2)',
                                    'success'
                                );
                                $('#delete-alert-modal').modal('hide');

                                // Remove the deleted todo from the list
                                $(`#todo-item-${todoId}`).remove();
                                setTimeout(function() {
                                    window.location.reload();
                                }, 1000);
                            }
                        },
                        error: function() {
                            $.NotificationApp.send(
                                'Error',
                                'Failed to delete todo. Please try again.',
                                'top-right',
                                'rgba(0,0,0,0.2)',
                                'error'
                            );
                        },
                    });


                });
                $(document).on('click', '.load-more', function(e) {
                    let button = $(e.target);
                    let nextPageUrl = button.data('next-page');

                    // Disable the button while loading
                    button.prop('disabled', true).text('Loading...');

                    $.ajax({
                        url: nextPageUrl,
                        method: 'GET',
                        success: function(response) {
                            let res;
                            if (button.data('status') === 'incomplete') {
                                res = response.todos.data;
                            } else {
                                res = response.completedTodos.data;
                            }

                            res.forEach(function(todo) {
                                let todoItem = `
                    <li class="d-flex justify-content-between align-items-start rounded px-3 py-2 mb-2">
                        <form action="/todos/${todo.name}/complete" class="d-flex align-items-center gap-3 mt-2" method="POST">
                            <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                            <div class="form-check m-0 px-2">
                                <input type="checkbox" class="form-check-input" onchange="markCompleted(${todo.id}, this)" 
                                    ${todo.completed ? 'checked' : ''}>
                                <span class="flex-grow-1 text-wrap text-break ${todo.completed ? 'striking' : ''}">${todo.name}</span>
                                  <span class="d-block text-muted">
    ${new Intl.DateTimeFormat('en-US', { 
        hour: 'numeric', 
        minute: 'numeric', 
        second: 'numeric', 
        hour12: true 
    }).format(new Date(todo.created_at))}, 
    ${new Intl.DateTimeFormat('en-US', { 
        day: 'numeric', 
        month: 'long', 
        year: 'numeric' 
    }).format(new Date(todo.created_at))}
         </span>
                            </div>
                        </form>
                        <div class="d-flex align-items-center justify-content-center">
                           
                            <a href="#editTaskModal" class="editTodo ms-2" data-bs-toggle="modal"
                                data-id="${todo.id}" data-title="${todo.name}" 
                                data-due-date="${todo.due_date || ''}" 
                                data-repeat-every="${todo.recurrence?.repeat_every || ''}" style="display: ${todo.completed ? 'none' : 'inline-block'};">
                                <i class="mdi mdi-circle-edit-outline mdi-24px"></i>
                            </a>

                            <a href="#delete-alert-modal" class="deleteTodo ms-2" data-bs-toggle="modal"
                                data-bs-title="${todo.name}" data-id="${todo.id}" 
                                data-rec-id="${todo.rec_id || ''}" data-bs-custom-class="danger-tooltip" 
                                data-bs-placement="top" data-bs-toggle="tooltip" title="Delete">
                                <i class="mdi mdi-delete-circle-outline mdi-24px" style="color: #ff5b5b"></i>
                            </a>
                        </div>
                    </li>
                `;


                                $(e.target).parent().parent().find('.todo-list').append(todoItem);
                            });

                            if (response.next_page_url) {
                                button.data('next-page', response.next_page_url);
                                button.prop('disabled', false).text('Load More');
                            } else {
                                $.NotificationApp.send(
                                    "Success",
                                    response.success,
                                    "top-right",
                                    "rgba(0,0,0,0.2)",
                                    "success"
                                );
                                button.remove(); // No more pages to load
                            }
                        },
                        error: function() {
                            button.prop('disabled', false).text('Load More');
                            $.NotificationApp.send(
                                "Error",
                                "Something went wrong, Please try again",
                                "top-right",
                                "rgba(0,0,0,0.2)",
                                "error"
                            );
                        }
                    });
                });

                // Mark a task as completed
                function markCompleted(todoId, checkbox) {
                    let isChecked = $(checkbox).is(':checked');
                    let form = $(checkbox).closest('form');
                    let listItem = $(checkbox).closest('li');
                    let url = isChecked ? `/todos/${todoId}/complete` : `/todos/${todoId}/incomplete`;

                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: form.serialize(),
                        success: function(response) {
                            if (isChecked) {
                                // Move the item to the completed list
                                $('#todo-list-completed').append(listItem);
                                listItem.find('span.text-wrap').css({
                                    'text-decoration': 'line-through',
                                    'text-decoration-thickness': '2px',
                                    'text-decoration-color': 'black'
                                });

                                // Remove the edit button
                                listItem.find('.editTodo').remove();
                            } else {
                                // Move the item back to the incomplete list
                                $('#todo-list').append(listItem);
                                listItem.find('span.text-wrap').css({
                                    'text-decoration': 'none'
                                });

                                // Restore the edit button
                                if (!listItem.find('.editTodo').length) {
                                    listItem.find('.deleteTodo').before(`
                        <a href="#editTaskModal" class="editTodo ms-2" data-bs-toggle="modal"
                            data-id="${todoId}">
                            <i class="mdi mdi-circle-edit-outline mdi-24px"></i>
                        </a>
                    `);
                                }
                            }
                        },
                        error: function() {
                            $.NotificationApp.send(
                                "Error",
                                "Failed to update the todo status. Please try again.",
                                "top-right",
                                "rgba(0,0,0,0.2)",
                                "error"
                            );
                            // Revert the checkbox state if the update fails
                            checkbox.checked = !isChecked;
                        }
                    });
                }





                // Mark a task as incomplete

                function markIncomplete(todoId, checkbox) {
                    let isChecked = $(checkbox).is(':checked');
                    let form = $(checkbox).closest('form');
                    let listItem = $(checkbox).closest('li');
                    let url = isChecked ? `/todos/${todoId}/complete` : `/todos/${todoId}/incomplete`;

                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: form.serialize(),
                        success: function(response) {
                            if (!isChecked) {
                                // Move the item back to the incomplete list
                                $('#todo-list').append(listItem);
                                listItem.find('span.text-wrap').css({
                                    'text-decoration': 'none'
                                });

                                // Restore the edit button
                                if (!listItem.find('.editTodo').length) {
                                    listItem.find('.deleteTodo').before(`
                        <a href="#editTaskModal" class="editTodo ms-2" data-bs-toggle="modal"
                            data-id="${todoId}">
                            <i class="mdi mdi-circle-edit-outline mdi-24px"></i>
                        </a>
                    `);
                                }
                            } else {
                                // Move the item to the completed list
                                $('#todo-list-completed').append(listItem);
                                listItem.find('span.text-wrap').css({
                                    'text-decoration': 'line-through',
                                    'text-decoration-thickness': '2px',
                                    'text-decoration-color': 'black'
                                });

                                // Remove the edit button
                                listItem.find('.editTodo').remove();
                            }
                        },
                        error: function() {
                            $.NotificationApp.send(
                                "Error",
                                "Failed to update the todo status. Please try again.",
                                "top-right",
                                "rgba(0,0,0,0.2)",
                                "error"
                            );
                            // Revert the checkbox state if the update fails
                            checkbox.checked = isChecked;
                        }
                    });
                }
            </script>
        @endpush
    @endsection
