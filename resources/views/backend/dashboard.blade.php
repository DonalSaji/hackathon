@extends('backend.body.master')
@section('title', 'Dashboard')
@section('content')


    <style>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">[data-bs-theme="dark"] input[data-switch]:checked+label:before {
            color: #fff;
        }

        table.dataTable td,
        table.dataTable th {
            vertical-align: middle;
        }

        .deleteTask {
            cursor: pointer;
            color: #ff5b5b;
        }

        .deleteTask:hover {
            text-decoration: none;
        }

        .modal-body img {
            width: 100%;
        }


        /* style for graphs */
        /* Chart Container */
        #salesPurchaseChart {
            width: 100%;
            height: 400px;
        }

        /* Filter Dropdown */
        #graphFilter {
            width: 100%;
            padding: 8px;
            font-size: 14px;
            border-radius: 5px;
            border: 1px solid #ced4da;
            background-color: #f8f9fa;
            cursor: pointer;
        }

        /* Card Styling */
        .card {
            border-radius: 8px;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Chart Tooltip Styling */
        .apexcharts-tooltip {
            background-color: rgba(0, 0, 0, 0.8) !important;
            color: white !important;
            border-radius: 5px !important;
        }

        /* Adjust legend position */
        .apexcharts-legend {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }

        /* Hover Effects */
        .apexcharts-series path:hover {
            opacity: 0.8;
        }

        .filter-btn {
            margin: 5px;
            /* Adds spacing between buttons */
            border-radius: 10px !important;
            /* Force rounded corners for all buttons */
            padding: 1px 10px;
            /* Adjusts button size */
        }

        .chart-content-bg {
            padding: 0.5rem;
            /* reduced padding */
            font-size: 0.9rem;
            /* optional: reduce text size */
            padding-top: 0.2rem;
            padding-bottom: 0.2rem;
        }



        /* Custom card styling */
        .card {
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border: 1px solid rgba(0, 0, 0, 0.075);
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }

        .card-title {
            font-size: 1rem;
            font-weight: 600;
        }

        .card-subtitle {
            font-size: 0.875rem;
        }

        .card-footer {
            padding-top: 0;
        }

        /* Make badges more subtle */
        .badge {
            font-weight: 500;
            padding: 0.35em 0.5em;
        }

        .NearestDue {
            height: 630px !important;
            display: flex;
            flex-direction: column;
        }

        .NearestDue .card-body {
            overflow-y: auto !important;
            /* Scroll only the content */
            max-height: 530px;
            /* Adjust for header height */
            contain: content;
        }

        .NearestDue .card-body::-webkit-scrollbar,
        .widget-card .card-body::-webkit-scrollbar {
            width: 7px;
        }

        .NearestDue .card-body::-webkit-scrollbar-track,
        .widget-card .card-body::-webkit-scrollbar-track {
            background-color: #F5F5F5;
            border-radius: 10px;
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
        }

        .NearestDue .card-body::-webkit-scrollbar-thumb,
        .widget-card .card-body::-webkit-scrollbar-thumb {
            border-radius: 10px;
            background-color: #536DE6;
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, .3);
        }

        .widget-card {
            height: 360px !important;
            display: flex;
            flex-direction: column;
        }

        .widget-card .card-body {
            overflow-y: auto !important;
            /* Scroll only the content */
            max-height: 280px;
            /* Adjust for header height */
        }

        @media (max-width: 1224px) {
            .pickup-card-column {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }

        @media (min-width: 1225px) {
            .pickup-card-column {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }

        @media (max-width: 1490px) {
            .NearestDue {
                height: 360px !important;
                /* Fixed total height for card */
                display: flex;
                flex-direction: column;
            }

            .NearestDue .card-body {
                flex: 1 1 auto;
                /* Let card-body take remaining space */
                overflow-y: auto !important;
                /* Scroll only the content */
                max-height: 280px;
                /* Adjust for header height */
            }
        }

        @media (max-width: 768px) {
            .pickup-card-row {
                margin-right: 0 !important;
                margin-left: 0 !important;
            }
        }

        .pickup-card-row {
            margin-right: 1.5rem;
            margin-bottom: 1.5rem;
            margin-left: 1.5rem;
        }

        @media (max-width: 576px) {
            #dateFilter {
                width: auto !important;
                min-width: 120px;
                flex: 1 1 auto;
            }

            #showLocationModal {
                padding: 6px 10px !important;
            }

            .responsive-header-actions {
                flex-direction: column !important;
                align-items: stretch !important;
            }
        }

        .report-card {
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .report-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .report-card .widget-icon {
            font-size: 18px;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .report-card h6 {
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            font-weight: 600;
            color: #6c757d;
        }

        .report-card h3 {
            font-size: 1.2rem;
            font-weight: 700;
        }

        .report-card p {
            font-size: 0.85rem !important;
        }

        @media (min-width: 1200px) and (max-width: 1490px) {

            .col-xl-2,
            .col-xl-3 {
                flex: 0 0 auto;
                width: 33.333333%;
                /* col-lg-4 */
            }

            .col-xl-4 {
                flex: 0 0 auto;
                width: 66.666667%;
                /* col-lg-8 */
            }

            .col-xl-8 {
                flex: 0 0 auto;
                width: 100%;
                /* col-lg-12 */
            }
        }
    </style>

    {{-- Page Title --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title text-center">Dashboard</h4>
            </div>
        </div>
    </div>

    {{-- Content Section --}}
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                {{-- tickets --}}
                {{-- @can('tickets')
                    <div class="col-xl-3 col-lg-4 col-md-6 ">
                        <div class="card widget-card">
                            <div class="card-header d-flex justify-content-between align-items-center mt-2 p-2">
                                <h5 class="mb-0 header-title fs-4">Tickets </h5>

                                @can('all.ticket')
                                    <a class="ms-2" href="{{ route('tickets') }}">
                                        <i class="ri-share-circle-line fs-3"></i>
                                    </a>
                                @endcan
                            </div>
                            <div class="card-body m-0 p-2">
                                <!-- Open Tickets -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="mb-1">Open Tickets</h6>
                                        <p class="mb-0">
                                            <strong>{{ $openTickets }}</strong> / <strong>{{ $totalTickets }}</strong>
                                        </p>
                                    </div>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-danger" role="progressbar"
                                            style="width: {{ $totalTickets > 0 ? ($openTickets / $totalTickets) * 100 : 0 }}%;"
                                            aria-valuenow="{{ $openTickets }}" aria-valuemin="0"
                                            aria-valuemax="{{ $totalTickets }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Closed Tickets -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="mb-1">Closed Tickets</h6>
                                        <p class="mb-0">
                                            <strong>{{ $closedTickets }}</strong> / <strong>{{ $totalTickets }}</strong>
                                        </p>
                                    </div>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-success" role="progressbar"
                                            style="width: {{ $totalTickets > 0 ? ($closedTickets / $totalTickets) * 100 : 0 }}%;"
                                            aria-valuenow="{{ $closedTickets }}" aria-valuemin="0"
                                            aria-valuemax="{{ $totalTickets }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Processing Tickets -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="mb-1">Processing Tickets</h6>
                                        <p class="mb-0">
                                            <strong>{{ $processingTickets }}</strong> /
                                            <strong>{{ $totalTickets }}</strong>
                                        </p>
                                    </div>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-info" role="progressbar"
                                            style="width: {{ $totalTickets > 0 ? ($processingTickets / $totalTickets) * 100 : 0 }}%;"
                                            aria-valuenow="{{ $processingTickets }}" aria-valuemin="0"
                                            aria-valuemax="{{ $totalTickets }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- On Hold Tickets -->
                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="mb-1">On Hold Tickets</h6>
                                        <p class="mb-0">
                                            <strong>{{ $onHoldTickets }}</strong> / <strong>{{ $totalTickets }}</strong>
                                        </p>
                                    </div>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-warning" role="progressbar"
                                            style="width: {{ $totalTickets > 0 ? ($onHoldTickets / $totalTickets) * 100 : 0 }}%;"
                                            aria-valuenow="{{ $onHoldTickets }}" aria-valuemin="0"
                                            aria-valuemax="{{ $totalTickets }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan --}}

                {{-- todo --}}
                @can('todo')
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        {{-- To-Do Card --}}
                        <div class="card widget-card">
                            <div class="card-header d-flex justify-content-between align-items-center p-2 m-0">
                                <h4 class="header-title mb-0 mt-2 fs-4">Todo</h4>
                                <div class="d-flex justify-content-between align-items-center mb-0">
                                    @can('all.todo')
                                        <a href="{{ route('todos.all_todos') }}" class="mt-2">View All</a>
                                    @endcan
                                    @can('add.todo')
                                        <a class="ms-2 mt-2" href="#" data-bs-toggle="modal" data-bs-target="#taskModal">
                                            <i class="mdi mdi-plus fs-2" class=""></i>
                                        </a>
                                    @endcan
                                </div>
                            </div>

                            <div class="card-body m-0 p-2">
                                {{-- Latest Todos --}}
                                @if ($todos->isNotEmpty())
                                    <div class="latest-todos">
                                        <h4 class="mt-0 mb-2">Latest To-Do's</h4>
                                        <ul class="ps-0 mt-0">
                                            @foreach ($todos as $todo)
                                                <li
                                                    class="d-flex justify-content-between align-items-start border rounded px-3 py-2 mb-2">
                                                    <form action="{{ route('todos.complete', $todo) }}"
                                                        class="d-flex align-items-center gap-3 mt-2" method="POST">
                                                        @csrf

                                                        <div class="form-check m-0 px-2">

                                                            <input type="checkbox" class="form-check-input"
                                                                onchange="this.form.submit()"
                                                                {{ $todo->completed ? 'checked' : '' }}>

                                                            <span
                                                                class="flex-grow-1 text-wrap text-break">{{ $todo->name }}</span>

                                                            <span class="d-block text-muted">Due:
                                                                {{ $todo->due_date ? \Carbon\Carbon::parse($todo->due_date)->endOfDay()->format('F d Y') : 'No Due Date' }}</span>
                                                        </div>
                                                    </form>

                                                    <div class="d-flex align-items-center justify-content-center">
                                                        @can('edit.todo')
                                                            <a href="#editTaskModal" class="editTodo ms-2" data-bs-toggle="modal"
                                                                data-id="{{ $todo->id }}" data-title="{{ $todo->name }}"
                                                                data-due-date="{{ $todo->due_date }}"
                                                                data-repeat-every="{{ $todo->recurrence->repeat_every ?? '' }}">
                                                                <i class="mdi mdi-circle-edit-outline mdi-24px"></i>
                                                            </a>
                                                        @endcan
                                                        @can('delete.todo')
                                                            <a href="#delete-alert-modal" class="deleteTodo ms-2"
                                                                data-bs-toggle="modal" data-bs-title="{{ $todo->title }}"
                                                                data-id="{{ $todo->id }}" data-rec-id="{{ $todo->rec_id }}"
                                                                data-bs-custom-class="danger-tooltip" data-bs-placement="top"
                                                                data-bs-toggle="tooltip" title="Delete">
                                                                <i class="mdi mdi-delete-circle-outline mdi-24px"
                                                                    style="color: #ff5b5b"></i>
                                                            </a>
                                                        @endcan

                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                {{-- Latest Finished Todos --}}
                                @if ($completedTodos->isNotEmpty())
                                    <div class="latest-finished-todos">
                                        <h4 class="mt-0 mb-2">Latest Finished To-Do's</h4>
                                        <ul class="ps-0">
                                            @foreach ($completedTodos as $todo)
                                                <li
                                                    class="d-flex justify-content-between align-items-start border rounded px-3 py-2 mb-2">

                                                    {{-- <input type="checkbox" checked>   --}}

                                                    <form action="{{ route('todos.incomplete', $todo) }}" method="POST"
                                                        class="d-flex align-items-center gap-3 mt-2">
                                                        @csrf

                                                        <div class="form-check m-0 px-2">
                                                            <input type="checkbox" class="form-check-input"
                                                                onchange="this.form.submit()" checked>

                                                            <span class="flex-grow-1 text-wrap text-break"
                                                                style="text-decoration: line-through;text-decoration-thickness: 2px;
                                                    text-decoration-color: black;">
                                                                {{ $todo->name }}
                                                            </span>
                                                            <span class="d-block text-muted">Due:
                                                                {{ $todo->due_date ? \Carbon\Carbon::parse($todo->due_date)->endOfDay()->format('F d Y') : 'No Due Date' }}</span>
                                                        </div>
                                                    </form>
                                                    {{-- Delete Option --}}
                                                    <div class="d-flex align-items-center justify-content-center">
                                                        <a href="#delete-alert-modal" class="deleteTodo ms-2"
                                                            data-bs-toggle="modal" data-bs-title="{{ $todo->title }}"
                                                            data-id="{{ $todo->id }}" data-rec-id="{{ $todo->rec_id }}"
                                                            data-bs-custom-class="danger-tooltip" data-bs-placement="top"
                                                            title="Delete">
                                                            <i class="mdi mdi-delete-circle-outline mdi-24px"
                                                                style="color: #ff5b5b"></i>
                                                        </a>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                {{-- End of Latest Finished Todos --}}
                            </div>
                        </div>
                    </div>
                @endcan

                {{-- tender application --}}
                {{-- @can('tender.status.widget')
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center mt-2 p-2">
                                <h5 class="mb-0 mt-1 header-title fs-4">Tender Applications</h5>
                            </div>
                            <div class="card-body m-0 p-2">
                                @php
                                    $applicationStatuses = [
                                        [
                                            'label' => 'Applied',
                                            'count' => $appliedTenders,
                                            'class' => 'bg-success',
                                            'route' => route('applied.tenders'), // Replace with your actual route
                                        ],
                                        [
                                            'label' => 'Pending',
                                            'count' => $pendingTenders,
                                            'class' => 'bg-danger',
                                            'route' => route('tenders'), // Replace with your actual route
                                        ],
                                    ];
                                @endphp

                                @foreach ($applicationStatuses as $status)
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="mb-1">{{ $status['label'] }} Tenders</h6>
                                            <p class="mb-0">
                                                <strong>{{ $status['count'] }}</strong> /
                                                <strong>{{ $totalAppliedPendingTenders }}</strong>
                                            </p>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="progress progress-sm flex-grow-1 me-2">
                                                <div class="progress-bar {{ $status['class'] }}" role="progressbar"
                                                    style="width: {{ $totalAppliedPendingTenders > 0 ? ($status['count'] / $totalAppliedPendingTenders) * 100 : 0 }}%;"
                                                    aria-valuenow="{{ $status['count'] }}" aria-valuemin="0"
                                                    aria-valuemax="{{ $totalAppliedPendingTenders }}">
                                                </div>
                                            </div>
                                            <a href="{{ $status['route'] }}">
                                                <i class="ri-share-circle-line fs-4"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endcan --}}


            </div>
        </div>
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

    {{-- Add Todo Modal --}}
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
                            <input type="number" class="form-control" name="custom_days" min="1"
                                value="1">
                            <span class="px-2">day(s)</span>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button id="saveTodo" type="submit" class="btn btn-primary">Save Todo</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- End Add Todo Modal --}}

    {{-- Edit Todo Modal --}}
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
                            @error('due_date')
                                <span class="text-muted mt-2">{{ $message }}</span>
                            @enderror
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
    {{-- End Edit Todo Modal --}}




    <div class="modal fade" id="locationModal" tabindex="-1" aria-labelledby="locationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Party Locations</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="locationModalBody">
                    <div class="text-center py-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    {{-- Custom Scripts --}}
    @push('custom-scripts')
        <script src="{{ asset('assets/vendor/daterangepicker/moment.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/daterangepicker/daterangepicker.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


        {{-- todo --}}
        <script>
            $(document).ready(function() {


                // Flatpickr for Date Input for add modal
                $("#due_date").flatpickr({
                    altInput: true,
                    altFormat: "F j, Y",
                    dateFormat: "Y-m-d",
                    defaultDate: "today",
                    minDate: "today",
                });

                // Show/Hide Custom Interval
                $('#repeat_every').on('change', function() {
                    $('.customContainer').toggleClass('d-none', $(this).val() !== 'Custom');
                });

                // Show/Hide Custom Interval in edit modal
                $('#edit_repeat_every').on('change', function() {
                    $('.customContainer').toggleClass('d-none', $(this).val() !== 'Custom');
                });

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

                                // $(".latest-todos ul").prepend(newTodoHtml);
                                $('#taskModal').modal('hide');
                                setTimeout(function() {
                                    // window.history.back(); // Redirect to the previous page
                                    window.location.reload();
                                }, 1000);
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
                //edit button
                $(document).on('click', '.editTodo', function() {

                    const todoId = $(this).data('id');
                    const todoTitle = $(this).data('title');
                    const todoDueDate = $(this).data('due-date');
                    const todoRepeatEvery = $(this).data('repeat-every');

                    $('#edit_todo_id').val(todoId);
                    $('#edit_title').val(todoTitle);
                    // $('#edit_due_date').val(todoDueDate);
                    $("#edit_due_date").flatpickr({
                        altInput: true,
                        altFormat: "F j, Y",
                        dateFormat: "Y-m-d",
                        defaultDate: todoDueDate,
                        minDate: "today",
                    });
                    $('#edit_repeat_every').val(todoRepeatEvery);
                });

                $('#editTaskForm').on('submit', function(e) {
                    e.preventDefault();

                    $(".text-danger").remove();

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
                        },
                    });
                });

                // Handle Delete Todo Modal
                $(document).on('click', '.deleteTodo', function() {
                    const todoId = $(this).data('id');
                    const todoTitle = $(this).data('bs-title');
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

                // Attach the confirmDelete event only once
                $(document).on('click', '.confirmDelete', function() {
                    const todoId = $('#delete-todo-id').val();
                    let deleteRecurrences = $('#todo-delete-checkbox-' + todoId).is(':checked') ? 1 : 0;

                    const csrfToken = $('meta[name="csrf-token"]').attr('content');

                    // AJAX request to delete todo and handle recurrence if needed
                    $.ajax({
                        url: `/todos/${todoId}`,
                        type: 'DELETE',
                        data: {
                            deleteRecurrences: deleteRecurrences,
                        },
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

            });
        </script>

        {{-- Graph --}}
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Format date as YYYY-MM-DD for API requests
                function formatDateForAPI(date) {
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const day = String(date.getDate()).padStart(2, '0');
                    return `${year}-${month}-${day}`;
                }


                const filterDropdown = document.querySelector(".filter-dropdown");

                if (filterDropdown) {
                    filterDropdown.addEventListener("change", function() {
                        const selectedFilter = this.value;

                        // Update active class on buttons (so they match dropdown)
                        document.querySelectorAll(".btn-group .btn").forEach(btn => {
                            if (btn.getAttribute("data-filter") === selectedFilter) {
                                btn.classList.add("active");
                            } else {
                                btn.classList.remove("active");
                            }
                        });

                        // Update the chart
                        updateGraph(selectedFilter);
                    });
                }

                document.querySelectorAll(".btn-group .btn").forEach(button => {
                    button.addEventListener("click", function() {
                        // Update active button
                        document.querySelectorAll(".btn-group .btn").forEach(btn =>
                            btn.classList.remove("active"));
                        this.classList.add("active");

                        // Update dropdown value to match the selected button (optional)
                        const filter = this.getAttribute("data-filter");
                        const dropdown = document.querySelector(".filter-dropdown");
                        if (dropdown) {
                            dropdown.value = filter;
                        }

                        // Update chart
                        updateGraph(filter);
                    });
                });


                // Format date for display (e.g., "19 Mar")
                function getFormattedDate(date = new Date()) {
                    return date.toLocaleDateString("en-GB", {
                        day: "2-digit",
                        month: "short"
                    });
                }

                // Get hours array for today's chart
                function getFormattedTime() {
                    let hours = new Date().getHours();
                    return Array.from({
                        length: hours + 1
                    }, (_, i) => `${i}:00`);
                }

                // Get all dates in current month
                function getDatesForMonth() {
                    let today = new Date();
                    let year = today.getFullYear();
                    let month = today.getMonth();
                    let daysInMonth = new Date(year, month + 1, 0).getDate();
                    return Array.from({
                            length: daysInMonth
                        }, (_, i) =>
                        getFormattedDate(new Date(year, month, i + 1)));
                }

                // Financial year months
                function getFinancialYearMonths() {
                    return ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar"];
                }

                // Get current financial year
                function getFinancialYear() {
                    let today = new Date();
                    let year = today.getFullYear();
                    return today.getMonth() <= 2 ? `${year - 1}-${year}` : `${year}-${year + 1}`;
                }

                const apiCache = new Map();

                // Similarly, convert other functions like:
                async function fetchPurchaseData(fromDate, toDate) {
                    const url = `/get-purchase-data?from_date=${fromDate}&to_date=${toDate}`;

                    try {
                        const response = await fetch(url);
                        const data = await response.json();

                        const dataLength = Array.isArray(data) ? data.length : 0;
                        const categories = Array.from({
                            length: dataLength
                        }, (_, i) => (i + 1).toString());

                        return {
                            purchase: Array.isArray(data) ? data.map(item => item?.value || 0) : [],
                        };
                    } catch (error) {
                        console.error("AJAX Purchase API Error:", error);
                        return {
                            purchase: [],
                            categories: []
                        };
                    }
                }

                // Similarly, convert other functions like:
                async function fetchSalesData(fromDate, toDate) {
                    const url = `/get-sales-data?from_date=${fromDate}&to_date=${toDate}`;

                    try {
                        const response = await fetch(url);
                        const data = await response.json();

                        const dataLength = Array.isArray(data) ? data.length : 0;
                        const categories = Array.from({
                            length: dataLength
                        }, (_, i) => (i + 1).toString());

                        return {
                            sales: Array.isArray(data) ? data.map(item => item?.value || 0) : [],
                        };
                    } catch (error) {
                        console.error("AJAX Sales API Error:", error);
                        return {
                            sales: [],
                            categories: []
                        };
                    }
                }


                // Fetch purchase data from day_totals_branch_payment API (used for weekly/monthly)
                async function fetchDatePurchaseData(fromDate, toDate) {
                    const url = `/get-date-purchase-data?from_date=${fromDate}&to_date=${toDate}`;

                    try {


                        const response = await fetch(url);
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        const data = await response.json();

                        const dataMap = new Map();
                        if (Array.isArray(data)) {
                            for (const item of data) {
                                if (item?.pmt_doc_date) {
                                    dataMap.set(item.pmt_doc_date, item.days_total || 0);
                                }
                            }
                        }

                        const result = {
                            purchase: [],
                            categories: []
                        };

                        let current = new Date(fromDate);
                        const end = new Date(toDate);

                        while (current <= end) {
                            const dateStr = current.toISOString().split("T")[0];
                            result.categories.push(dateStr);
                            result.purchase.push(dataMap.get(dateStr) || 0);
                            current.setDate(current.getDate() + 1);
                        }

                        apiCache.set(url, result);
                        return result;

                    } catch (error) {
                        console.error("Date Purchase API Error:", error);
                        return {
                            purchase: [],
                            categories: []
                        };
                    }
                }

                // Fetch sale data from day_totals_branch_payment API (used for weekly/monthly)
                async function fetchDateSalesData(fromDate, toDate) {
                    const url = `/get-date-sales-data?from_date=${fromDate}&to_date=${toDate}`;

                    try {
                        if (apiCache.has(url)) {
                            return apiCache.get(url);
                        }


                        const response = await fetch(url);
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        const data = await response.json();

                        const dataMap = new Map();
                        if (Array.isArray(data)) {
                            for (const item of data) {
                                if (item?.pmt_doc_date) {
                                    dataMap.set(item.pmt_doc_date, item.days_total || 0);
                                }
                            }
                        }

                        const result = {
                            sales: [],
                            categories: []
                        };

                        let current = new Date(fromDate);
                        const end = new Date(toDate);

                        while (current <= end) {
                            const dateStr = current.toISOString().split("T")[0];
                            result.categories.push(dateStr);
                            result.sales.push(dataMap.get(dateStr) || 0);
                            current.setDate(current.getDate() + 1);
                        }

                        apiCache.set(url, result);
                        return result;

                    } catch (error) {
                        console.error("Date Sales API Error:", error);
                        return {
                            sales: [],
                            categories: []
                        };
                    }
                }

                // Fetch purchase data from day_totals_branch_payment API (yearly/ Quarter)
                async function fetchMonthPurchaseData(fromDate, toDate) {
                    const url = `/get-date-purchase-data?from_date=${fromDate}&to_date=${toDate}`;

                    try {
                        if (apiCache.has(url)) {
                            return apiCache.get(url);
                        }

                        const response = await fetch(url);
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        const data = await response.json();

                        // Create a map of existing data by date
                        const dataMap = new Map();
                        if (Array.isArray(data)) {
                            for (const item of data) {
                                if (item?.pmt_doc_date) {
                                    dataMap.set(item.pmt_doc_date, item.days_total || 0);
                                }
                            }
                        }

                        const result = {
                            purchase: [],
                            categories: [],
                            xAxisLabels: [] // Only show on 1st or 15th
                        };

                        let current = new Date(fromDate);
                        const end = new Date(toDate);

                        while (current <= end) {
                            const dateStr = current.toISOString().split("T")[0];

                            result.categories.push(dateStr);
                            result.purchase.push(dataMap.get(dateStr) || 0);

                            const day = current.getDate();
                            result.xAxisLabels.push((day === 1) ? dateStr : "");

                            current.setDate(current.getDate() + 1);
                        }

                        apiCache.set(url, result);
                        return result;

                    } catch (error) {
                        console.error("Date Purchase API Error:", error);
                        return {
                            purchase: [],
                            categories: [],
                            xAxisLabels: []
                        };
                    }
                }

                // Fetch sale data from day_totals_branch_payment API (yearly/ Quarter)
                async function fetchMonthSalesData(fromDate, toDate) {
                    const url =
                        `/get-date-sales-data?from_date=${fromDate}&to_date=${toDate}`;

                    try {
                        if (apiCache.has(url)) {
                            return apiCache.get(url);
                        }

                        const response = await fetch(url);
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        const data = await response.json();
                        //console.log(data);
                        const dataMap = new Map();
                        if (Array.isArray(data)) {
                            for (const item of data) {
                                if (item?.pmt_doc_date) {
                                    dataMap.set(item.pmt_doc_date, item.days_total || 0);
                                }
                            }
                        }

                        const result = {
                            sales: [],
                            categories: [],
                            xAxisLabels: []
                        };

                        let current = new Date(fromDate);
                        const end = new Date(toDate);

                        while (current <= end) {
                            const dateStr = current.toISOString().split("T")[0];

                            result.categories.push(dateStr);
                            result.sales.push(dataMap.get(dateStr) || 0);

                            const day = current.getDate();
                            result.xAxisLabels.push((day === 1) ? dateStr : "");

                            current.setDate(current.getDate() + 1);
                        }

                        apiCache.set(url, result);
                        return result;

                    } catch (error) {
                        console.error("Date Sales API Error:", error);
                        return {
                            sales: [],
                            categories: [],
                            xAxisLabels: []
                        };
                    }
                }




                // Initialize chart with empty data
                const chart = new ApexCharts(document.querySelector("#salesPurchaseChart"), {
                    series: [{
                            name: "Sales",
                            data: []
                        },
                        {
                            name: "Purchase",
                            data: []
                        }
                    ],
                    chart: {
                        type: "area",
                        height: 400,
                        toolbar: {
                            show: false
                        },
                        animations: {
                            enabled: true
                        }
                    },
                    colors: ["#28a745", "#0000FF"], // Green for Sales, Blue for Purchase
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        curve: "smooth",
                        width: 2
                    },
                    fill: {
                        type: "gradient",
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.5,
                            opacityTo: 0.3
                        }
                    },
                    xaxis: {
                        categories: [],
                        title: {
                            text: "Loading..."
                        },
                        labels: {
                            rotate: -45
                        }
                    },
                    yaxis: {
                        title: {
                            text: "Amount"
                        },
                        min: 0
                    },

                    tooltip: {
                        enabled: true,
                        theme: "dark",
                        style: {
                            fontSize: "14px"
                        },
                        // x: {
                        //     formatter: function(val) {
                        //         return val; // It will be the full date like "2025-04-07"
                        //     }
                        // },

                        y: {
                            formatter: function(value) {
                                return value.toLocaleString();
                            }
                        }
                    },
                    legend: {
                        position: "top"
                    }
                });
                chart.render();

                // Normalize data to match categories length
                function normalizeData(data, length) {
                    if (!data || data.length === 0) return Array(length).fill(0);
                    return data.length >= length ?
                        data.slice(0, length) : [...data, ...Array(length - data.length).fill(0)];
                }

                // Get date range for selected filter
                function getDateRange(filter) {
                    const today = new Date();
                    let fromDate, toDate, defaultCategories, xAxisTitle;

                    updateSinceLabel(filter)

                    switch (filter) {
                        case "today": {
                            fromDate = toDate = formatDateForAPI(today);

                            const yesterday = new Date(today);
                            yesterday.setDate(yesterday.getDate() - 1);
                            pfromDate = ptoDate = formatDateForAPI(yesterday);

                            defaultCategories = getFormattedTime();
                            xAxisTitle = "Today - " + getFormattedDate();
                            break;
                        }

                        case "this_week": {
                            const weekStart = new Date(today);
                            weekStart.setDate(today.getDate() - today.getDay());

                            const lastWeekStart = new Date(weekStart);
                            lastWeekStart.setDate(weekStart.getDate() - 7);

                            const lastWeekEnd = new Date(weekStart);
                            lastWeekEnd.setDate(weekStart.getDate() - 1);

                            fromDate = formatDateForAPI(weekStart);
                            toDate = formatDateForAPI(today);

                            pfromDate = formatDateForAPI(lastWeekStart);
                            ptoDate = formatDateForAPI(lastWeekEnd);

                            defaultCategories = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
                            xAxisTitle = "This Week";
                            break;
                        }

                        case "last_week": {
                            const lastWeekStart = new Date(today);
                            lastWeekStart.setDate(today.getDate() - today.getDay() - 7);

                            const lastWeekEnd = new Date(lastWeekStart);
                            lastWeekEnd.setDate(lastWeekStart.getDate() + 6);

                            const prevWeekStart = new Date(lastWeekStart);
                            prevWeekStart.setDate(lastWeekStart.getDate() - 7);

                            const prevWeekEnd = new Date(lastWeekEnd);
                            prevWeekEnd.setDate(lastWeekEnd.getDate() - 7);

                            fromDate = formatDateForAPI(lastWeekStart);
                            toDate = formatDateForAPI(lastWeekEnd);

                            pfromDate = formatDateForAPI(prevWeekStart);
                            ptoDate = formatDateForAPI(prevWeekEnd);

                            defaultCategories = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
                            xAxisTitle = "Last Week";
                            break;
                        }

                        case "this_month": {
                            const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
                            fromDate = formatDateForAPI(startOfMonth);
                            toDate = formatDateForAPI(today);

                            const prevMonthStart = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                            const prevMonthEnd = new Date(today.getFullYear(), today.getMonth(), 0);
                            pfromDate = formatDateForAPI(prevMonthStart);
                            ptoDate = formatDateForAPI(prevMonthEnd);

                            defaultCategories = getDatesForMonth();
                            xAxisTitle = today.toLocaleString("default", {
                                month: "long"
                            });
                            break;
                        }

                        case "this_quarter": {
                            const quarter = Math.floor(today.getMonth() / 3);
                            const year = today.getFullYear();

                            const quarterStart = new Date(year, quarter * 3, 1);
                            const quarterEnd = new Date(year, (quarter + 1) * 3, 0);
                            fromDate = formatDateForAPI(quarterStart);
                            toDate = formatDateForAPI(quarterEnd);

                            const prevQuarterStart = new Date(quarterStart);
                            prevQuarterStart.setMonth(quarterStart.getMonth() - 3);
                            const prevQuarterEnd = new Date(quarterStart);
                            prevQuarterEnd.setDate(0); // Last day of previous quarter
                            pfromDate = formatDateForAPI(prevQuarterStart);
                            ptoDate = formatDateForAPI(prevQuarterEnd);

                            defaultCategories = ["Month 1", "Month 2", "Month 3"];
                            xAxisTitle = `This Quarter`;
                            break;
                        }

                        case "this_year": {
                            const fyStart = today.getMonth() >= 3 ? today.getFullYear() : today.getFullYear() - 1;
                            const fyEnd = fyStart + 1;

                            fromDate = `${fyStart}-04-01`;
                            toDate = `${fyEnd}-03-31`;

                            pfromDate = `${fyStart - 1}-04-01`;
                            ptoDate = `${fyStart}-03-31`;

                            defaultCategories = getFinancialYearMonths();
                            xAxisTitle = `FY ${fyStart}-${fyEnd}`;
                            break;
                        }

                        default: {
                            fromDate = toDate = formatDateForAPI(new Date());

                            const yesterday = new Date(today);
                            yesterday.setDate(yesterday.getDate() - 1);
                            pfromDate = ptoDate = formatDateForAPI(yesterday);

                            defaultCategories = getFormattedTime();
                            xAxisTitle = "Today - " + getFormattedDate();
                        }
                    }


                    return {
                        fromDate,
                        toDate,
                        pfromDate,
                        ptoDate,
                        defaultCategories,
                        xAxisTitle
                    };
                }

                // Update chart based on filter selection
                async function updateGraph(filter) {
                    try {
                        // Show loading state
                        chart.updateOptions({
                            xaxis: {
                                title: {
                                    text: "Loading data..."
                                }
                            }
                        });

                        // Get date range for filter
                        const {
                            fromDate,
                            toDate,
                            defaultCategories,
                            xAxisTitle
                        } = getDateRange(filter);

                        // Determine which API functions to use
                        let salesPromise, purchasePromise, psalesPromise, ppurchasePromise;

                        if (filter === "today") {
                            // Use brief_sales_summary for today's data
                            salesPromise = fetchSalesData(fromDate, toDate);
                            purchasePromise = fetchPurchaseData(fromDate, toDate);

                            //previous period
                            psalesPromise = fetchSalesData(pfromDate, ptoDate);
                            ppurchasePromise = fetchPurchaseData(pfromDate, ptoDate);
                        } else if (filter === "this_quarter" || filter === "this_year") {
                            // Use brief_sales_summary for today's data
                            salesPromise = fetchMonthSalesData(fromDate, toDate);
                            purchasePromise = fetchMonthPurchaseData(fromDate, toDate);

                            //previous period
                            psalesPromise = fetchMonthSalesData(pfromDate, ptoDate);
                            ppurchasePromise = fetchMonthPurchaseData(pfromDate, ptoDate);
                        } else {
                            // Use day_totals_branch_payment for weekly/monthly data
                            salesPromise = fetchDateSalesData(fromDate, toDate);
                            purchasePromise = fetchDatePurchaseData(fromDate, toDate);

                            //previous period
                            psalesPromise = fetchDateSalesData(pfromDate, ptoDate);
                            ppurchasePromise = fetchDatePurchaseData(pfromDate, ptoDate);
                        }


                        // Fetch data with timeout
                        const controller = new AbortController();
                        const timeout = setTimeout(() => controller.abort(), 15000);

                        const [salesResult, purchaseResult] = await Promise.allSettled([
                            salesPromise,
                            purchasePromise
                        ]);

                        //previous period
                        const [psalesResult, ppurchaseResult] = await Promise.allSettled([
                            psalesPromise,
                            ppurchasePromise
                        ]);


                        if (salesResult.status === 'fulfilled' && psalesResult.status === 'fulfilled') {
                            updateMetric('sales', salesResult.value.sales, psalesResult.value.sales);
                        } else {
                            console.error('Sales data failed:', salesResult.reason);
                            console.error('Previous sales data failed:', psalesResult.reason);
                        }

                        if (purchaseResult.status === 'fulfilled' && ppurchaseResult.status === 'fulfilled') {
                            updateMetric('purchase', purchaseResult.value.purchase, ppurchaseResult.value.purchase);
                        } else {
                            console.error('Purchase data failed:', purchaseResult.reason);
                            console.error('Previous purchase data failed:', ppurchaseResult.reason);
                        }








                        clearTimeout(timeout);

                        // Process results
                        const salesData = salesResult.status === 'fulfilled' ? salesResult.value : {
                            sales: [],
                            categories: []
                        };
                        const purchaseData = purchaseResult.status === 'fulfilled' ? purchaseResult.value : {
                            purchase: [],
                            categories: []
                        };

                        let categories;
                        let sales, purchase;

                        if (filter === "today") {
                            // For today filter - use sequential numbers based on longest dataset
                            const maxLength = Math.max(
                                salesData.sales?.length || 0,
                                purchaseData.purchase?.length || 0
                            );
                            categories = Array.from({
                                length: maxLength
                            }, (_, i) => (i + 1).toString());
                            sales = normalizeData(salesData.sales, maxLength);
                            purchase = normalizeData(purchaseData.purchase, maxLength);
                        } else {
                            // For other filters - use original category logic
                            categories = salesData.categories?.length ? salesData.categories :
                                purchaseData.categories?.length ? purchaseData.categories :
                                defaultCategories;
                            sales = normalizeData(salesData.sales, categories.length);
                            purchase = normalizeData(purchaseData.purchase, categories.length);
                        }



                        // Update chart
                        if (filter === "this_quarter") {
                            // Get the detailed daily data
                            const [salesResult, purchaseResult] = await Promise.allSettled([
                                fetchMonthSalesData(fromDate, toDate),
                                fetchMonthPurchaseData(fromDate, toDate)
                            ]);

                            const salesData = salesResult.status === 'fulfilled' ? salesResult.value : {
                                sales: [],
                                categories: [],
                                xAxisLabels: []
                            };
                            const purchaseData = purchaseResult.status === 'fulfilled' ? purchaseResult.value : {
                                purchase: [],
                                categories: [],
                                xAxisLabels: []
                            };

                            // Update chart with all data points but filtered labels
                            chart.updateOptions({
                                xaxis: {
                                    categories: salesData.categories, // Full dates for tooltips
                                    title: {
                                        text: xAxisTitle
                                    },
                                    labels: {
                                        formatter: function(value) {
                                            // Only show labels for 1st and 15th of month
                                            const date = new Date(value);
                                            const day = date.getDate();
                                            if (day === 1 || day === 15) {
                                                return date.toLocaleDateString('en-US', {
                                                    month: 'short',
                                                    day: 'numeric'
                                                });
                                            }
                                            return "";
                                        },
                                        rotate: -0
                                    }
                                }
                            });

                            chart.updateSeries([{
                                    name: "Sales",
                                    data: salesData.sales
                                },
                                {
                                    name: "Purchase",
                                    data: purchaseData.purchase
                                }
                            ]);
                        } else if (filter === "this_year") {
                            // Get the detailed daily data
                            const [salesResult, purchaseResult] = await Promise.allSettled([
                                fetchMonthSalesData(fromDate, toDate),
                                fetchMonthPurchaseData(fromDate, toDate)
                            ]);

                            const salesData = salesResult.status === 'fulfilled' ? salesResult.value : {
                                sales: [],
                                categories: [],
                                xAxisLabels: []
                            };
                            const purchaseData = purchaseResult.status === 'fulfilled' ? purchaseResult.value : {
                                purchase: [],
                                categories: [],
                                xAxisLabels: []
                            };

                            // Update chart with all data points but filtered labels
                            chart.updateOptions({
                                xaxis: {
                                    categories: salesData.categories, // Full dates for tooltips
                                    title: {
                                        text: xAxisTitle
                                    },
                                    labels: {
                                        formatter: function(value) {
                                            // Only show labels for 1st and 15th of month
                                            const date = new Date(value);
                                            const day = date.getDate();
                                            if (day === 1) {
                                                return date.toLocaleDateString('en-US', {
                                                    month: 'short',
                                                    day: 'numeric'
                                                });
                                            }
                                            return "";
                                        },
                                        rotate: -0
                                    }
                                }
                            });

                            chart.updateSeries([{
                                    name: "Sales",
                                    data: salesData.sales
                                },
                                {
                                    name: "Purchase",
                                    data: purchaseData.purchase
                                }
                            ]);
                        } else if (filter === "today") {
                            chart.updateOptions({
                                xaxis: {
                                    categories: categories,
                                    title: {
                                        text: xAxisTitle
                                    },
                                    labels: {}
                                },
                                yaxis: {
                                    min: Math.min(0, ...sales, ...purchase),
                                    max: Math.max(10, ...sales, ...purchase) * 1.1
                                }
                            });
                        } else {
                            chart.updateOptions({
                                xaxis: {
                                    categories: categories,
                                    title: {
                                        text: xAxisTitle
                                    },
                                    labels: {
                                        formatter: function(value) {
                                            // Only show labels for 1st and 15th of month
                                            const date = new Date(value);
                                            const day = date.getDate();

                                            return date.toLocaleDateString('en-GB', {
                                                month: 'short',
                                                day: 'numeric'
                                            });

                                        },
                                    }
                                },
                                yaxis: {
                                    min: Math.min(0, ...sales, ...purchase),
                                    max: Math.max(10, ...sales, ...purchase) * 1.1
                                }
                            });
                        }



                        chart.updateSeries([{
                                name: "Sales",
                                data: sales
                            },
                            {
                                name: "Purchase",
                                data: purchase
                            }
                        ]);

                    } catch (error) {
                        console.error("Error updating graph:", error);
                        chart.updateOptions({
                            xaxis: {
                                title: {
                                    text: "Failed to load data"
                                }
                            }
                        });
                        chart.updateSeries([{
                                name: "Sales",
                                data: []
                            },
                            {
                                name: "Purchase",
                                data: []
                            }
                        ]);
                    }
                }

                // Set up filter buttons
                document.querySelectorAll(".btn-group .btn").forEach(button => {
                    button.addEventListener("click", function() {
                        // Update active button
                        document.querySelectorAll(".btn-group .btn").forEach(btn =>
                            btn.classList.remove("active"));
                        this.classList.add("active");

                        // Update chart
                        const filter = this.getAttribute("data-filter");
                        updateGraph(filter);
                    });
                });

                // Load initial data
                updateGraph("today");

                // Refresh data every 5 minutes
                setInterval(() => {
                    const activeFilter = document.querySelector(".btn-group .btn.active")?.getAttribute(
                        "data-filter");
                    if (activeFilter) updateGraph(activeFilter);
                }, 300000); // 5 minutes
            });


            function updateMetric(type, currentData, previousData) {
                const total = currentData.reduce((sum, val) => sum + val, 0);
                const prevTotal = previousData.reduce((sum, val) => sum + val, 0);
                const diff = total - prevTotal;

                const percentChange = prevTotal !== 0 ? (diff / prevTotal) * 100 : (total > 0 ? 100 : 0);
                const formattedPercent = percentChange.toFixed(2);
                const formattedTotal = total.toLocaleString('en-IN', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                document.getElementById(`${type}Total`).textContent = formattedTotal;

                const changeEl = document.getElementById(`${type}PercentChange`);
                if (changeEl) {
                    changeEl.innerHTML = '';

                    const isSales = type === 'sales';
                    const icon = document.createElement('i');

                    let colorClass = 'text-success';
                    let iconClass = 'mdi-arrow-up-bold'; // default for 0%

                    if (percentChange > 0) {
                        iconClass = 'mdi-arrow-up-bold';
                        colorClass = isSales ? 'text-success' : 'text-danger';
                    } else if (percentChange < 0) {
                        iconClass = 'mdi-arrow-down-bold';
                        colorClass = 'text-success'; // only purchase < 0 is green, sales < 0 is red
                        if (isSales) colorClass = 'text-danger';
                    }

                    icon.className = `mdi ${iconClass} me-1`;

                    changeEl.className = `me-1 d-flex align-items-center ${colorClass}`;
                    changeEl.append(icon, document.createTextNode(`${formattedPercent}%`));
                }
            }




            function updateSinceLabel(filter) {
                const labelMap = {
                    today: 'Since previous day',
                    this_week: 'Since previous week',
                    last_week: 'Since previous week',
                    this_month: 'Since previous month',
                    this_quarter: 'Since previous quarter',
                    this_year: 'Since previous FY'
                };
                const label = labelMap[filter] || 'Since previous period';
                document.getElementById('sinceLabel').textContent = label;
                document.getElementById('sinceLabels').textContent = label;
            }
        </script>

        {{-- refilling --}}
        <script>
            $(document).ready(function() {
                loadRefillingDue();
                // loadPickupAssigned();
                // loadDeliveryAssigned();
                loadAssignedData(); // Initial call
            });



            let lastClickedElement = null;





            function toggleButton(detailId) {
                const selectedUser = document.getElementById(`user_select_${detailId}`).value;
                const button = document.getElementById(`btn_${detailId}`);
                button.disabled = selectedUser === "";
            }

            function loadRefillingDue() {
                $.ajax({
                    url: '/refilling-due',
                    method: 'GET',
                    success: function(data) {
                        let html = '';
                        const today = new Date();

                        if (data.length > 0) {
                            html += `
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm mb-0" style="table-layout: fixed; width: 100%;">
                                                <thead class="table-light text-center">
                                                    <tr>
                                                        <th class="text-wrap" style="max-width: 10%;">Reference No.</th>
                                                        <th class="text-wrap" style="max-width: 60%;">Party Name</th>
                                                        <th class="text-wrap" style="max-width: 20%;">Deadline</th>
                                                    </tr>
                                                </thead>
                                                <tbody>`;

                            data.forEach(item => {
                                const dueDate = new Date(item.due_date);
                                const daysLeft = Math.ceil((dueDate - today) / (1000 * 60 * 60 * 24));

                                let rowClass = '';
                                if (daysLeft <= 1) {
                                    rowClass = 'bg-danger-subtle'; // today or tomorrow
                                } else if (daysLeft <= 4) {
                                    rowClass = 'bg-warning-subtle'; // next 3–4 days
                                } else {
                                    rowClass = 'bg-light-subtle'; // next 5–7 days
                                }


                                html += `<tr class="${rowClass}">
                                            <td class="text-wrap">${item.reference_id}</td>
                                            <td class="text-wrap">${item.partyname}</td>
                                            <td class="text-wrap text-center">${formatDate(item.deadline)}</td>
                                        </tr>`;
                            });

                            html += `
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>`;
                        } else {
                            html = '<div class="p-2 text-muted">No pending deliveries found.</div>';
                        }



                        $('#dueDateTbody').html(html);
                    },
                    error: function(err) {
                        $('#dueDateTbody').html(
                            '<tr><td colspan="3" class="text-danger text-center">Failed to load data.</td></tr>'
                        );
                        console.error("Error loading refilling due:", err);
                    }
                });
            }




            function loadAssignedData(filter = 'today') {
                $('#assignedDataContainer').html(`<div class="text-center p-3">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>`);

                $.ajax({
                    url: '/assigned-grouped',
                    method: 'GET',
                    data: {
                        day: filter
                    },

                    success: function(response) {
                        let html = '';

                        function renderGroupBlock(entries, title, type) {
                            const cardColor = type === 'pending' ? 'danger' : 'success';
                            const icon = type === 'pending' ? 'mdi-clock-outline' : 'mdi-check-circle-outline';
                            const label = type === 'pending' ? 'Pending Tasks' : 'Completed Tasks';

                            if (entries.length === 0) return;

                            html += `
        <div class="card border-${cardColor} mb-4">
            <div class="card-header bg-${cardColor} bg-opacity-25 text-dark d-flex align-items-center gap-2">
                <i class="mdi ${icon} fs-5"></i><span><h6>${label}</h6></span>
            </div>
            <div class="card-body p-2">`;

                            entries.forEach(entry => {
                                html += `
            <div class="card mb-3 shadow-sm border-0">
                <div class="card-header bg-light d-flex justify-content-between align-items-center px-2 py-2 flex-wrap">
                    <div>
                        <div class="fw-bold text-dark" style="text-transform:uppercase;">${entry.partyname}</div>
                        <div class="text-muted small">${entry.address}</div>
                    </div>
                </div>
                <div class="card-body p-1 d-flex flex-column gap-2">`;

                                entry.items.forEach(item => {
                                    const isPickup = item.type === 'pickup';
                                    const isDelivery = item.type === 'delivery';
                                    const isOnsite = item.type === 'On Site';

                                    const bgClass = isPickup ?
                                        'bg-primary bg-opacity-25' :
                                        isDelivery ?
                                        'bg-info bg-opacity-25' :
                                        'bg-warning bg-opacity-25';

                                    const iconClass = isPickup ?
                                        'mdi-truck-fast-outline text-primary' :
                                        isDelivery ?
                                        'mdi-package-variant-closed text-info' :
                                        'mdi-fuel text-warning';

                                    const arrowColor = isPickup ?
                                        'text-primary' :
                                        isDelivery ?
                                        'text-info' :
                                        'text-warning';

                                    const actionLink = isOnsite ?
                                        `/refuelling-request/stage/${item.id}` :
                                        `/${item.type}/${item.id}`;

                                    const assignedTime = item.assigned_date ? formatTime(item
                                        .assigned_date, true) : '';
                                    const completedTime = item.delivered_date ? formatTime(item
                                        .delivered_date, true) : '';

                                    const isOverdue = !item.completed && new Date(item
                                        .assigned_date) < new Date().setHours(0, 0, 0, 0);
                                    const overdueBadge = isOverdue ?
                                        `<span class="badge rounded-pill bg-danger bg-opacity-75 text-white px-1 py-1 small">overdue</span>` :
                                        '';

                                    if (type === 'completed') {
                                        html += `
                    <div class="rounded d-flex justify-content-between align-items-center px-1 py-2 flex-wrap">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <i class="mdi ${iconClass} fs-5"></i>
                            <span class="fw-semibold" style="font-size: 12px;">${item.reference_id}</span>
                            <small class="text-muted">${completedTime}</small>
                        </div>
                        <span><i class="mdi mdi-check-circle-outline mdi-18px text-success"></i></span>
                    </div>`;
                                    } else {
                                        html += `
                    <a href="${actionLink}" class="text-decoration-none">
                        <div class="rounded ${bgClass} d-flex justify-content-between align-items-center px-1 py-2 flex-wrap">
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <i class="mdi ${iconClass} fs-5"></i>
                                <span class="fw-semibold text-dark" style="font-size: 12px;">${item.reference_id}</span>
                                ${
                                    isOnsite
                                        ? `<span class="badge bg-warning">Onsite</span>`
                                        : `<small class="text-muted">${assignedTime}</small>`
                                }
                                ${overdueBadge}
                            </div>
                            <i class="mdi mdi-arrow-right fs-5 ${arrowColor}"></i>
                        </div>
                    </a>`;
                                    }
                                });

                                html += `</div></div>`;
                            });

                            html += `</div></div>`;
                        }


                        if (response.pending.length === 0 && response.completed.length === 0) {
                            html =
                                `<div class="text-center text-muted p-4"><i class="mdi mdi-inbox-remove-outline fs-1"></i><h5>No tasks found for this day</h5></div>`;
                        } else {
                            renderGroupBlock(response.pending, 'Pending', 'pending');
                            renderGroupBlock(response.completed, 'Completed', 'completed');
                        }

                        $('#assignedDataContainer').html(html);
                    },

                    error: function(err) {
                        $('#assignedDataContainer').html(
                            `<div class="text-center text-danger p-3"><i class="mdi mdi-alert-circle-outline fs-1"></i><h5>Error loading data</h5></div>`
                        );
                        console.error("Error loading assigned data:", err);
                    }
                });
            }

            $('#dateFilter').on('change', function() {
                loadAssignedData(this.value);
            });


            $('#showLocationModal').on('click', function() {
                const filter = $('#dateFilter').val();

                $('#locationModalBody').html(`<div class="text-center py-3">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>`);

                $('#locationModal').modal('show');

                $.ajax({
                    url: '/assigned-grouped',
                    method: 'GET',
                    data: {
                        day: filter
                    },

                    success: function(response) {
                        let html = '';

                        const renderSection = (entries, title, color = 'warning', icon =
                            'mdi-clock-outline') => {
                            if (entries.length === 0) return;

                            html += `
            <div class="mb-4">
                <div class="d-flex align-items-center mb-2">
                    <i class="mdi ${icon} text-${color} fs-5 me-2"></i>
                    <h6 class="mb-0 fw-bold text-${color}">${title}</h6>
                </div>
                <ul class="list-group list-group-flush ps-1">`;

                            entries.forEach(entry => {
                                html += `
                <li class="list-group-item border-0 ps-0 position-relative">
                    <div class="ps-3 border-start border-3 border-${color} d-flex align-items-start gap-2">
                        <i class="mdi mdi-map-marker text-muted fs-5"></i>
                        <div class="small text-muted">${entry.address}</div>
                    </div>
                </li>`;
                            });

                            html += `</ul></div>`;
                        };

                        renderSection(response.location_pending, 'Pending Locations', 'warning',
                            'mdi-map-clock-outline');
                        renderSection(response.location_completed, 'Completed Locations', 'success',
                            'mdi-check-circle-outline');

                        if (html === '') {
                            html = `<div class="text-center text-muted py-3">
            <i class="mdi mdi-map-marker-off-outline fs-1"></i>
            <h5>No locations found</h5>
        </div>`;
                        }

                        $('#locationModalBody').html(html);
                    },

                    error: function(err) {
                        $('#locationModalBody').html(
                            `<div class="text-center text-danger py-3"><i class="mdi mdi-alert-circle-outline fs-1"></i><h5>Error loading locations</h5></div>`
                        );
                        console.error("Error loading location modal data:", err);
                    }
                });
            });




            function formatTime(datetimeStr, onlyTime = false) {
                const date = new Date(datetimeStr);
                const options = {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                };
                return date.toLocaleTimeString('en-US', options);
            }


            function formatDate(dateString) {
                if (!dateString) return '';
                const date = new Date(dateString);
                const options = {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric'
                };
                // Format with toLocaleString
                let formatted = date.toLocaleString('en-gb', options);

                // Capitalize first letter of month and ensure AM/PM is uppercase
                formatted = formatted.replace(/([ap]m)/, match => match.toUpperCase());

                return formatted;
                // return date.toLocaleString('en-gb', options).toUpperCase();
            }

            function formatDateTime(dateString) {
                if (!dateString) return '';
                const date = new Date(dateString);

                // Date formatting options
                const dateOptions = {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric'
                };

                // Time formatting options
                const timeOptions = {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                };

                // Format date and time separately
                const formattedDate = date.toLocaleString('en-gb', dateOptions);
                const formattedTime = date.toLocaleString('en-gb', timeOptions);

                // Capitalize first letter of month and AM/PM
                const finalDate = formattedDate.replace(/\b\w/g, char => char.toUpperCase());
                const finalTime = formattedTime.toUpperCase();

                return `${finalDate}<br> ${finalTime}`;
            }
        </script>
        <script>
            window.csrfToken = "{{ csrf_token() }}";
        </script>

        <script type="module" src="{{ asset('assets/js/webauthn-db.js') }}"></script>
        <script type="module" src="{{ asset('assets/js/biometric-register.js') }}"></script>
    @endpush

@endsection
