@extends('backend.body.master')
@section('title', 'All Roles')
@section('content')

    @push('custom-css')
        <!-- Datatables css -->
        <link href="{{ asset('assets/vendor/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet"
            type="text/css" />
        <link href="{{ asset('assets/vendor/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet"
            type="text/css" />
    @endpush

    <style>
        /* Custom Blue Tooltip */
        .blue-tooltip.bs-tooltip-top .tooltip-arrow {
            border-top-color: #007bff;
            /* Blue arrow */
        }

        .blue-tooltip.bs-tooltip-top .tooltip-inner {
            background-color: #007bff;
            /* Blue background */
            color: #fff;
            /* White text */
            border-radius: 5px;
            /* Optional: Adds rounded corners */
            padding: 5px 10px;
            /* Optional: Adjusts padding */
        }

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
    </style>

    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">

            @if (Auth::user()->can('add.roles'))
                <div class="row mt-3">
                    <div class="col-12 text-right" style="z-index:2;">
                        <button type="button" class="btn btn-primary float-end"
                            onclick="window.location.href='{{ route('add.roles') }}'">
                            <span class="addRolebtn1">Add Role</span>
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
                    <h4 class="page-title">Roles</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">

                <table id="basic-datatable" class="table activate-select dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>SL No.</th>
                            <th>Roles Name</th>
                            <th>User Count</th>
                            @can('edit.rolesinpermission')
                                <th>Permission</th>
                            @endcan
                            @canany(['edit.roles', 'delete.roles'])
                                <th>Action</th>
                            @endcanany
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($roles) && $roles->count() > 0)
                            @foreach ($roles as $role)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ ucfirst($role->name) }}</td>
                                    <td>{{ $role->users_count }}</td>
                                    @can('edit.rolesinpermission')
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="me-2"></span>
                                                @can('edit.rolesinpermission')
                                                    <a href="{{ route('edit.roles.permission', $role->id) }}"
                                                        class="text-primary editPermissionBtn" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" data-bs-custom-class="info-tooltip"
                                                        title="Edit Permission">
                                                        <i class="mdi mdi-eye mdi-24px"></i>
                                                    </a>
                                                @endcan
                                            </div>
                                        </td>
                                    @endcan
                                    @canany(['edit.roles', 'delete.roles'])
                                        <td>
                                            @if ($role->defined_by === 'system')
                                                System Defined
                                            @else
                                                <div class="d-flex flex-wrap">
                                                    @can('edit.roles')
                                                        <a href="{{ route('edit.roles', $role->id) }}" class="editRolebtn"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            data-bs-custom-class="info-tooltip" data-bs-title="Edit"><i
                                                                class="mdi mdi-circle-edit-outline mdi-24px"></i></a>
                                                    @endcan
                                                    @can('delete.roles')
                                                        <a class="deleteRole ms-2" deleid="{{ $role->id }}"
                                                            delesno="{{ $role->name }}" data-bs-toggle="tooltip"
                                                            data-bs-placement="top" data-bs-custom-class="danger-tooltip"
                                                            data-bs-title="Delete"><i
                                                                class="mdi mdi-delete-circle-outline mdi-24px"></i></a>
                                                    @endcan
                                                </div>
                                            @endif
                                        </td>
                                    @endcanany
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center">No roles found.</td>
                            </tr>
                        @endif

                    <tbody>
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
                    <h5 class="modal-title" id="scrollableModalTitle">Delete Roles </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete Role <b><span id="alert-Role-sno"></span></b>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger deleteButton">Delete</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    @push('custom-scripts')
        <!-- Datatables js -->
        <script src="{{ asset('assets/vendor/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>


        <script>
            $(document).ready(function() {
                "use strict";
                $('[data-bs-toggle="tooltip"]').each(function() {
                    new bootstrap.Tooltip(this);
                });

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

                $(document).on("click", ".deleteRole", function(e) {
                    $("#alert-Role-sno").html($(e.target.parentNode).attr("delesno"));
                    var delE = "deleteRole(" + $(e.target.parentNode).attr("deleid") + ")";
                    $(".deleteButton").attr("onclick", delE);
                    $("#delete-alert-modal").modal("show");
                });
                // Initialize tooltips
                // $('[data-bs-toggle="tooltip"]').tooltip(); //see this
            });

            function deleteRole(id) {
                const csrfToken = document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content");

                var formData = new FormData();
                formData.append("id", id);
                $.ajax({
                    url: "{{ route('delete.roles') }}",
                    type: "POST",
                    contentType: false,
                    processData: false,
                    headers: {
                        "X-CSRF-Token": csrfToken,
                    },
                    data: formData,
                    success: function(response) {
                        // Handle the success response
                        if (response.error) {
                            $.NotificationApp.send(
                                "Error",
                                response.error,
                                "top-right",
                                "rgba(0,0,0,0.2)",
                                "error"
                            );
                        } else {
                            $("#delete-alert-modal").modal("hide");
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
                        let resMessage = "An unknown error occurred.";
                        try {
                            const response = JSON.parse(xhr.responseText);
                            resMessage = response.error || response.message || resMessage;
                        } catch (e) {
                            // responseText is not JSON
                            resMessage = xhr.responseText || resMessage;
                        }

                        $.NotificationApp.send(
                            "Error",
                            resMessage,
                            "top-right",
                            "rgba(0,0,0,0.2)",
                            "error"
                        );
                    },

                });
            }
        </script>
    @endpush
@endsection
