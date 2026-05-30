@extends('backend.body.master')
@section('title', 'All Users')
@section('content')

    @push('custom-css')
        <!-- Datatables css -->
        <link href="{{ asset('assets/vendor/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet"
            type="text/css" />
        <link href="{{ asset('assets/vendor/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet"
            type="text/css" />
        <link href="{{ asset('assets/vendor/datatables.net-fixedcolumns-bs5/css/fixedColumns.bootstrap5.min.css') }}"
            rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/vendor/datatables.net-fixedheader-bs5/css/fixedHeader.bootstrap5.min.css') }}"
            rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/vendor/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css') }}" rel="stylesheet"
            type="text/css" />
        <link href="{{ asset('assets/vendor/datatables.net-select-bs5/css/select.bootstrap5.min.css') }}" rel="stylesheet"
            type="text/css" />
    @endpush

    <style>
        input[data-switch]:checked+label:before {
            left: -2px;
            top: 20px;
            color: #75797d;
        }

        [data-bs-theme="dark"] input[data-switch]:checked+label:before {
            color: #fff;
        }

        table.dataTable td,
        table.dataTable th {
            vertical-align: middle;
        }

        .deleteRole {
            cursor: pointer;
            color: #ff5b5b;
        }

        .addRolebtn2 {
            display: none;
        }

        @media(max-width:576px) {
            .addRolebtn1 {
                display: none;
            }

            .addRolebtn2 {
                display: block;
            }
        }

        #state-saving-datatable tbody td:last-child {
            min-width: 90px;
            /* Set your desired minimum width */
        }

        .modal-body img {
            width: 100%;
        }

        #viewRoleModal hr {
            margin-top: 0.75rem;
            margin-bottom: 0.75rem;
        }

        /* Device Options Styling */
        .device-options {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .device-option {
            position: relative;
        }

        .device-checkbox {
            display: none;
        }

        .device-label {
            display: flex;
            align-items: center;
            padding: 20px;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .device-label:hover {
            border-color: #007bff;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .device-checkbox:checked+.device-label {
            border-color: #007bff;
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.15);
        }

        .device-icon {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            margin-right: 15px;
            transition: all 0.3s ease;
        }

        .device-checkbox:checked+.device-label .device-icon {
            background: #007bff;
            color: white;
        }

        .device-icon i {
            font-size: 1.5rem;
            color: #6c757d;
        }

        .device-checkbox:checked+.device-label .device-icon i {
            color: white;
        }

        .device-info {
            flex: 1;
        }

        .device-name {
            font-weight: 600;
            color: #0d72d6;
            margin-bottom: 4px;
            font-size: 1.1rem;
        }

        .device-desc {
            color: #6c757d;
            font-size: 0.875rem;
            margin: 0;
        }

        .device-check {
            width: 24px;
            height: 24px;
            border: 2px solid #dee2e6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            opacity: 0;
        }

        .device-checkbox:checked+.device-label .device-check {
            background: #05ad3d;
            border-color: #05ad3d;
            opacity: 1;
        }

        .device-check i {
            color: white;
            font-size: 0.75rem;
        }

        /* Button Loading States */
        .btn-loading {
            display: none;
        }

        .btn-text {
            display: inline-block;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .device-label {
                padding: 15px;
            }

            .device-icon {
                width: 40px;
                height: 40px;
                margin-right: 12px;
            }

            .device-icon i {
                font-size: 1.25rem;
            }
        }

        .dataTables_length {
            margin-bottom: 10px;
        }

        .user-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }

        .role-badge {
            font-size: 0.75rem;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .action-buttons a,
        .action-buttons button {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
        }
    </style>

    <!-- Start Content-->
    <div class="container-fluid">
        <!-- Page title and action button -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        @can('add.admin')
                            <button type="button" class="btn btn-primary"
                                onclick="window.location.href='{{ route('add.admin') }}'">
                                <span class="addRolebtn1">Add User</span>
                                <span class="addRolebtn2"><i class="mdi mdi-plus-circle-outline mdi-24px"></i></span>
                            </button>
                        @endcan
                    </div>
                    <h4 class="page-title">All Users</h4>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-3">
            <div class="col-xl-3 col-lg-6">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="mdi mdi-account-multiple widget-icon"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0" title="Number of Users">Total Users</h5>
                        {{-- <h3 class="mt-3 mb-3">{{ $Users->count() }}</h3> --}}
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="mdi mdi-account-check widget-icon text-success"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0" title="Active Users">Active Users</h5>
                        {{-- <h3 class="mt-3 mb-3">{{ $Users->where('status', 'active')->count() }}</h3> --}}
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="mdi mdi-shield-account widget-icon text-primary"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0" title="Admin Users">Admin Users</h5>
                        {{-- <h3 class="mt-3 mb-3">
                            {{ $Users->whereHas('roles', function ($q) {$q->where('name', 'admin');})->count() }}</h3> --}}
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="mdi mdi-account-clock widget-icon text-warning"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0" title="Inactive Users">Inactive Users</h5>
                        <h3 class="mt-3 mb-3">{{ $Users->where('status', 'inactive')->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="users-datatable" class="table table-hover table-centered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>User</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Last Login</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($Users as $index => $user)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <img src="{{ asset($user->photo ?? 'assets/images/users/avatar-1.jpg') }}"
                                                            alt="user-image" class="rounded-circle avatar-xs">
                                                    </div>
                                                    <div class="flex-grow-1 ms-2">
                                                        <h5 class="m-0 font-14">{{ $user->name }}</h5>
                                                        <p class="mb-0 text-muted">ID: {{ $user->id }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="mailto:{{ $user->email }}"
                                                    class="text-body">{{ $user->email }}</a>
                                            </td>
                                            <td>{{ $user->phone ?? 'N/A' }}</td>
                                            <td>
                                                @if ($user->roles->isNotEmpty())
                                                    @foreach ($user->roles as $role)
                                                        <span
                                                            class="badge bg-primary role-badge">{{ $role->name }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="badge bg-secondary">No Role</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($user->status == 'active')
                                                    <span class="badge bg-success">Active</span>
                                                @elseif($user->status == 'inactive')
                                                    <span class="badge bg-warning">Inactive</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $user->status }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($user->last_login_at)
                                                    {{ $user->last_login_at->format('d M Y, h:i A') }}
                                                @else
                                                    <span class="text-muted">Never</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    @can('edit.admin')
                                                        <a href="{{ route('edit.admin', $user->id) }}"
                                                            class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip"
                                                            title="Edit User">
                                                            <i class="mdi mdi-pencil-outline"></i>
                                                        </a>
                                                    @endcan

                                                    @can('view.admin')
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-primary view-user-btn"
                                                            data-id="{{ $user->id }}" data-bs-toggle="tooltip"
                                                            title="View Details">
                                                            <i class="mdi mdi-eye-outline"></i>
                                                        </button>
                                                    @endcan

                                                    @can('device.access')
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-success device-access-btn"
                                                            data-id="{{ $user->id }}" data-name="{{ $user->name }}"
                                                            data-bs-toggle="tooltip" title="Device Access">
                                                            <i class="mdi mdi-cellphone-link"></i>
                                                        </button>
                                                    @endcan

                                                    @can('delete.admin')
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-danger delete-user-btn"
                                                            data-id="{{ $user->id }}" data-name="{{ $user->name }}"
                                                            data-bs-toggle="modal" data-bs-target="#delete-alert-modal"
                                                            data-bs-toggle="tooltip" title="Delete User">
                                                            <i class="mdi mdi-delete-outline"></i>
                                                        </button>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end container-fluid -->

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="delete-alert-modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="mdi mdi-alert-circle-outline me-2"></i>Delete User
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete <strong id="delete-user-name"></strong>?</p>
                    <p class="text-danger mb-0"><small>This action cannot be undone.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirm-delete">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Device Access Modal -->
    <div class="modal fade" id="device-access-modal" tabindex="-1">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="mdi mdi-cellphone-link me-2"></i>Device Access Settings
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info mb-4">
                        <i class="mdi mdi-information-outline me-2"></i>
                        <strong id="user-name"></strong> has access to the selected device types.
                    </div>

                    <form id="device-access-form">
                        <input type="hidden" name="user_id" id="device_user_id">

                        <div class="device-options">
                            <!-- Desktop Option -->
                            <div class="device-option">
                                <input type="checkbox" name="allowed_devices[]" value="desktop" id="desktop"
                                    class="device-checkbox">
                                <label for="desktop" class="device-label border">
                                    <div class="device-icon">
                                        <i class="mdi mdi-desktop-tower"></i>
                                    </div>
                                    <div class="device-info">
                                        <h6 class="device-name">Desktop</h6>
                                        <p class="device-desc">Windows, Mac, Linux computers</p>
                                    </div>
                                    <div class="device-check">
                                        <i class="mdi mdi-check"></i>
                                    </div>
                                </label>
                            </div>

                            <!-- Mobile Option -->
                            <div class="device-option">
                                <input type="checkbox" name="allowed_devices[]" value="mobile" id="mobile"
                                    class="device-checkbox">
                                <label for="mobile" class="device-label border">
                                    <div class="device-icon">
                                        <i class="mdi mdi-cellphone"></i>
                                    </div>
                                    <div class="device-info">
                                        <h6 class="device-name">Mobile</h6>
                                        <p class="device-desc">Smartphones and small screens</p>
                                    </div>
                                    <div class="device-check">
                                        <i class="mdi mdi-check"></i>
                                    </div>
                                </label>
                            </div>

                            <!-- Tablet Option -->
                            <div class="device-option">
                                <input type="checkbox" name="allowed_devices[]" value="tablet" id="tablet"
                                    class="device-checkbox">
                                <label for="tablet" class="device-label border">
                                    <div class="device-icon">
                                        <i class="mdi mdi-tablet"></i>
                                    </div>
                                    <div class="device-info">
                                        <h6 class="device-name">Tablet</h6>
                                        <p class="device-desc">iPads, Android tablets</p>
                                    </div>
                                    <div class="device-check">
                                        <i class="mdi mdi-check"></i>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="mdi mdi-close me-2"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-primary" id="saveDeviceAccess">
                        <span class="btn-loading" style="display: none;">
                            <i class="mdi mdi-loading mdi-spin me-2"></i>Saving...
                        </span>
                        <span class="btn-text">
                            <i class="mdi mdi-content-save-outline me-2"></i>Save Settings
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('custom-scripts')
        <!-- Datatables js -->
        <script src="{{ asset('assets/vendor/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
        <script>
            $(document).ready(function() {
                // Initialize DataTable
                $('#users-datatable').DataTable({
                    order: [
                        [0, 'asc']
                    ],
                    pageLength: 10,
                    responsive: true,
                    language: {
                        paginate: {
                            previous: "<i class='mdi mdi-chevron-left'>",
                            next: "<i class='mdi mdi-chevron-right'>"
                        }
                    },
                    columnDefs: [{
                            orderable: false,
                            targets: [7]
                        },
                        {
                            searchable: false,
                            targets: [0, 6, 7]
                        }
                    ]
                });

                // Initialize tooltips
                $('[data-bs-toggle="tooltip"]').tooltip();

                // Delete user functionality
                let deleteUserId = null;
                let deleteUserName = null;

                $(document).on('click', '.delete-user-btn', function() {
                    deleteUserId = $(this).data('id');
                    deleteUserName = $(this).data('name');
                    $('#delete-user-name').text(deleteUserName);
                });

                $('#confirm-delete').on('click', function() {
                    if (!deleteUserId) return;

                    $(this).prop('disabled', true).html(
                        '<i class="mdi mdi-loading mdi-spin me-2"></i>Deleting...');

                    $.ajax({
                        url: '{{ route('delete.admin') }}',
                        type: 'POST',
                        data: {
                            id: deleteUserId,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            $('#delete-alert-modal').modal('hide');

                            if (response.success) {
                                // Remove the row from table
                                $(`button[data-id="${deleteUserId}"]`).closest('tr').fadeOut(300,
                                    function() {
                                        $(this).remove();
                                        // Reload page to update stats
                                        setTimeout(() => location.reload(), 300);
                                    });

                                $.NotificationApp.send(
                                    "Success",
                                    response.message,
                                    "top-right",
                                    "rgba(0,0,0,0.2)",
                                    "success"
                                );
                            } else {
                                $.NotificationApp.send(
                                    "Error",
                                    response.message,
                                    "top-right",
                                    "rgba(0,0,0,0.2)",
                                    "error"
                                );
                            }
                        },
                        error: function(xhr) {
                            let errorMessage = "An error occurred while deleting the user.";
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                            $.NotificationApp.send(
                                "Error",
                                errorMessage,
                                "top-right",
                                "rgba(0,0,0,0.2)",
                                "error"
                            );
                        },
                        complete: function() {
                            $('#confirm-delete').prop('disabled', false).html('Delete');
                        }
                    });
                });

                // Device access modal functionality
                $(document).on('click', '.device-access-btn', function() {
                    const userId = $(this).data('id');
                    const userName = $(this).data('name');

                    $('#device_user_id').val(userId);
                    $('#user-name').text(userName);

                    // Reset checkboxes
                    $('#device-access-form input[type=checkbox]').prop('checked', false);
                    $('#saveDeviceAccess').prop('disabled', true);

                    // Fetch existing device settings
                    $.get(`/user/${userId}/devices`, function(response) {
                        if (response.allowed_devices && response.allowed_devices.length > 0) {
                            response.allowed_devices.forEach(device => {
                                $(`#device-access-form input[value="${device}"]`).prop(
                                    'checked', true);
                            });
                        }
                    }).fail(function() {
                        $.NotificationApp.send(
                            "Warning",
                            "Could not load device settings",
                            "top-right",
                            "rgba(0,0,0,0.2)",
                            "warning"
                        );
                    }).always(function() {
                        $('#saveDeviceAccess').prop('disabled', false);
                    });

                    $('#device-access-modal').modal('show');
                });

                // Save device access settings
                $('#saveDeviceAccess').on('click', function() {
                    const $btn = $(this);
                    const $loading = $btn.find('.btn-loading');
                    const $btnText = $btn.find('.btn-text');

                    // Check if at least one device is selected
                    const checkedDevices = $('#device-access-form input[type=checkbox]:checked').length;
                    if (checkedDevices === 0) {
                        $.NotificationApp.send(
                            "Warning",
                            "Please select at least one device",
                            "top-right",
                            "rgba(0,0,0,0.2)",
                            "warning"
                        );
                        return;
                    }

                    // Show loading state
                    $loading.show();
                    $btnText.hide();
                    $btn.prop('disabled', true);

                    $.ajax({
                        url: '{{ route('update.allowed.devices') }}',
                        type: 'POST',
                        data: $('#device-access-form').serialize() + '&_token={{ csrf_token() }}',
                        success: function(response) {
                            if (response.success) {
                                $.NotificationApp.send(
                                    "Success",
                                    response.message,
                                    "top-right",
                                    "rgba(0,0,0,0.2)",
                                    "success"
                                );
                                $('#device-access-modal').modal('hide');
                            } else {
                                $.NotificationApp.send(
                                    "Error",
                                    response.message,
                                    "top-right",
                                    "rgba(0,0,0,0.2)",
                                    "error"
                                );
                            }
                        },
                        error: function(xhr) {
                            let errorMessage = "An error occurred while saving settings.";
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                            $.NotificationApp.send(
                                "Error",
                                errorMessage,
                                "top-right",
                                "rgba(0,0,0,0.2)",
                                "error"
                            );
                        },
                        complete: function() {
                            $loading.hide();
                            $btnText.show();
                            $btn.prop('disabled', false);
                        }
                    });
                });

                // Reset modal on close
                $('#device-access-modal').on('hidden.bs.modal', function() {
                    $('#device-access-form')[0].reset();
                    $('#user-name').text('');
                });
            });
        </script>
    @endpush
@endsection
