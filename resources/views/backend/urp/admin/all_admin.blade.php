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
            /* adjust value as needed */
        }
    </style>

    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">

            @if (Auth::user()->can('add.admin'))
                <div class="row mt-3">
                    <div class="col-12 text-right" style="z-index:2;">
                        <button type="button" class="btn btn-primary float-end"
                            onclick="window.location.href='{{ route('add.admin') }}'">
                            <span class="addRolebtn1">Add User</span>
                            <span class="addRolebtn2"><i class="mdi mdi-plus-circle-outline mdi-24px"></i></span>
                        </button>
                    </div>
                </div>
                <div class="row" style="margin-top: -55px;">
                @else
                    <div class="row">
            @endif

            <div class="col-12 text-center">
                <div class="page-title-box">
                    <h4 class="page-title">All Users</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">

                <table id="basic-datatable" class="table activate-select dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>SL No.</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Assigned Role</th>
                            @canany(['edit.admin', 'delete.admin'])
                                <th>Actions</th>
                            @endcanany

                        </tr>
                    </thead>

                    <tbody>
                        @if (!empty($allUsers))
                            @foreach ($allUsers as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user['name'] }}</td>
                                    <td>{{ $user['email'] }}</td>
                                    <td>{{ $user['phone'] }}</td>
                                    <td>{{ $user['role'] }}</td>
                                    @canany(['edit.admin', 'delete.admin'])
                                        <td>
                                            <div class="d-flex">
                                                @can('edit.admin')
                                                    <a href="{{ route('edit.admin', $user['id']) }}" class="editRolebtn me-2"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        data-bs-custom-class="info-tooltip" data-bs-title="Edit">
                                                        <i class="mdi mdi-circle-edit-outline mdi-24px"></i>
                                                    </a>
                                                @endcan

                                                @can('delete.admin')
                                                    <a href="#" class="delete-user me-2 deleteuserBtn"
                                                        data-id="{{ $user['id'] }}" data-bs-toggle="modal"
                                                        data-bs-target="#delete-alert-modal" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" data-bs-custom-class="danger-tooltip"
                                                        title="Delete">
                                                        <i class="mdi mdi-delete-circle-outline mdi-24px text-danger"></i>
                                                    </a>
                                                @endcan
                                            </div>
                                        </td>
                                    @endcanany

                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="text-center">No data</td>
                            </tr>
                        @endif


                    </tbody>
                </table>

            </div>
        </div>
    </div>
    </div>

    <!--delete alert modal -->
    <div class="modal fade" id="delete-alert-modal" tabindex="-1" role="dialog" aria-labelledby="deleteAlertModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this user?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger confirm-delete">Delete</button>
                </div>
            </div>
        </div>
    </div>





    @push('custom-scripts')
        <!-- Datatables js -->
        <script src="{{ asset('assets/vendor/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>

        <script>
            $(document).ready(function() {

                $("#basic-datatable").DataTable({

                    keys: true, // Enables keyboard navigation
                    language: {
                        paginate: {
                            previous: "<i class='mdi mdi-chevron-left'></i>", // Custom left arrow
                            next: "<i class='mdi mdi-chevron-right'></i>" // Custom right arrow
                        }
                    },
                    drawCallback: function() {
                        $(".dataTables_paginate > .pagination").addClass(
                            "pagination-rounded"); // Apply rounded styling to pagination
                    }
                });


                var deleteUserId = null;

                // Capture the user ID when delete button is clicked
                $(document).on('click', '.delete-user', function() {
                    deleteUserId = $(this).data('id');
                });

                // Confirm delete action
                $('.confirm-delete').on('click', function() {
                    $.ajax({
                        url: '{{ route('delete.admin') }}',
                        type: 'POST',
                        data: {
                            id: deleteUserId,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            // Handle the success response
                            $("#delete-alert-modal").modal("hide");
                            if (!response.sucess) {
                                $.NotificationApp.send(
                                    "Error",
                                    response.message,
                                    "top-right",
                                    "rgba(0,0,0,0.2)",
                                    "error"
                                );
                            } else {
                                var table = $("#state-saving-datatable").DataTable();
                                // Redraw the table
                                table.draw(); // Pass false to prevent resetting the paging

                                $.NotificationApp.send(
                                    "Success",
                                    response.message,
                                    "top-right",
                                    "rgba(0,0,0,0.2)",
                                    "success"
                                );
                            }
                        },
                        error: function(xhr, status, error) {
                            // Handle the error response
                            var res = xhr.responseText;
                            if (isJSON(xhr.responseText)) {
                                var jsonObject = JSON.parse(xhr.responseText);
                                res = jsonObject.message;
                            }
                            $.NotificationApp.send(
                                "Error",
                                res,
                                "top-right",
                                "rgba(0,0,0,0.2)",
                                "error"
                            );
                        },

                    });
                });
            });
        </script>
    @endpush
@endsection
