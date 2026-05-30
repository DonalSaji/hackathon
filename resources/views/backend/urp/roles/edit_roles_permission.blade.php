@extends('backend.body.master')
@section('title', 'All Roles Permission')
@section('content')
    <style>
        .backEditPermissionbtn2 {
            display: none;
        }

        @media(max-width:576px) {
            .backEditPermissionbtn1 {
                display: none;
            }

            .backEditPermissionbtn2 {
                display: block;
            }
        }
    </style>

    <style type="text/css">
        .form-check-label {
            text-transform: capitalize;
        }
    </style>

    <div class="row mt-3">
        <div class="col-12" style="z-index:2;">
            <button type="button" class="btn btn-primary backEditPermissionbtn1"
                onclick="window.location.href='{{ route('all.roles') }}'">
                <span><i class="mdi mdi-arrow-left-thin"></i> Back to Roles </span>
            </button>
            <a href="{{ route('all.roles') }}" class="backEditPermissionbtn2"><i
                    class="mdi mdi-arrow-left-thin-circle-outline mdi-24px"></i></a>
        </div>
    </div>

    <div class="row" style="margin-top: -55px;">
        <div class="col-12 text-center">
            <div class="page-title-box">
                <h4 class="page-title">Permissions in Role</h4>
            </div>
        </div>
    </div>
    <div class="page-content">
        <div class="row profile-body justify-content-center align-items-center">

            <div class="col-md-10 col-xl-10 middle-wrapper">
                <div class="row ">
                    <div class="card ">
                        <div class="card-body ">

                            <form class="forms-sample" id="myForm" method="post"
                                action="{{ route('update.roles.permission', $role->id) }}">
                                @csrf

                                <div class="form-group mb-3">
                                    <label for="exampleInputEmail1" class="form-label">Role Name</label>
                                    <h3>{{ ucfirst($role->name) }}</h3>
                                </div>

                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input" id="checkAllPermissions">
                                    <label class="form-check-label" for="checkAllPermissions">
                                        Allow All Permissions
                                    </label>
                                </div>

                                <hr>

                                @foreach ($permission_groups as $group)
                                    <div class="row align-items-start  mb-3">
                                        <div class="col-md-2 mb-2 bg-light bg-gradient p-2 ms-2">
                                            @php
                                                $permissions = App\Models\User::getPermissionByGroupName(
                                                    $group->group_name,
                                                );
                                            @endphp

                                            <div class="form-check ">
                                                <input type="checkbox" class="form-check-input group-checkbox"
                                                    id="group_{{ $loop->index }}"
                                                    {{ App\Models\User::roleHasPermissions($role, $permissions) ? 'checked' : '' }}>
                                                <label class="form-check-label fw-bold" for="group_{{ $loop->index }}">
                                                    {{ $group->group_name }}
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-8 mx-2">
                                            <div class="row ms-3">
                                                @foreach ($permissions as $permission)
                                                    <div class="col-md-4 mb-2">
                                                        <div class="form-check">
                                                            <input type="checkbox"
                                                                class="form-check-input permission-checkbox group_{{ $loop->parent->index }}"
                                                                name="permission[]" id="permission_{{ $permission->id }}"
                                                                value="{{ $permission->id }}"
                                                                {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                                            <label class="form-check-label"
                                                                for="permission_{{ $permission->id }}">
                                                                {{ $permission->name }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach






                                <button type="submit" class="btn btn-primary me-2 ">Save Changes</button>
                            </form>

                        </div>
                    </div>

                </div>
            </div>
            <!-- middle wrapper end -->
            <!-- right wrapper start -->

            <!-- right wrapper end -->
        </div>

    </div>


    @push('custom-scripts')
        <script type="text/javascript">
            $(document).ready(function() {
                // Main "Permission All" checkbox functionality
                $('#checkAllPermissions').click(function() {
                    $('input[type=checkbox]').prop('checked', $(this).is(':checked'));
                });

                // Each group's checkbox to select/deselect all its permissions
                $('.group-checkbox').click(function() {
                    const groupId = $(this).attr('id'); // Get this group’s ID
                    $(`.${groupId}`).prop('checked', $(this).is(':checked'));
                    updateCheckAllPermissions();
                });

                // Automatically check/uncheck group checkbox based on its permissions
                $('.permission-checkbox').change(function() {
                    const groupClass = $(this).attr('class').split(' ').pop(); // Get the group class
                    const groupCheckbox = $(`#${groupClass}`);
                    const allPermissions = $(`.${groupClass}`);
                    const anyChecked = allPermissions.filter(':checked').length > 0;

                    // Keep the group checkbox checked if any permission is selected
                    groupCheckbox.prop('checked', anyChecked);
                    updateCheckAllPermissions();
                });

                // Function to update the "Permission All" checkbox based on individual permissions
                function updateCheckAllPermissions() {
                    const allPermissions = $('.permission-checkbox');
                    const allChecked = allPermissions.length === allPermissions.filter(':checked').length;
                    $('#checkAllPermissions').prop('checked', allChecked);
                }

                // Initial check to set the "Permission All" checkbox state
                updateCheckAllPermissions();
            });
        </script>
    @endpush
@endsection
