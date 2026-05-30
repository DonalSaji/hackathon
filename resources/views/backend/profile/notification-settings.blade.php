@extends('backend.body.master')
@section('title', 'Notification Settings')
@section('content')
    <style>
        .backEditNotificationsbtn2 {
            display: none;
        }

        @media(max-width:576px) {
            .backEditNotificationsbtn1 {
                display: none;
            }

            .backEditNotificationsbtn2 {
                display: block;
            }
        }

        .form-check-label {
            text-transform: capitalize;
        }

        .notification-group {
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .notification-group:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }
    </style>

    {{-- <div class="row mt-3">
        <div class="col-12" style="z-index:2;">
            <button type="button" class="btn btn-primary backEditNotificationsbtn1"
                onclick="window.location.href='{{ route('all.roles') }}'">
                <span><i class="mdi mdi-arrow-left-thin"></i> Back to Roles </span>
            </button>
            <a href="{{ route('all.Notifications') }}" class="backEditNotificationsbtn2"><i
                    class="mdi mdi-arrow-left-thin-circle-outline mdi-24px"></i></a>
        </div>
    </div> --}}

    <div class="row">
        <div class="col-12 text-center">
            <div class="page-title-box">
                <h4 class="page-title">Notification Settings</h4>
            </div>
        </div>
    </div>
    <div class="page-content">
        <div class="row justify-content-center align-items-center">
            <div class="col-12 middle-wrapper">
                <div class="card ">
                    <div class="card-body ">
                        <form class="forms-sample" id="myForm" method="post"
                            action="{{ route('update.notification.settings', $user->id) }}">
                            @csrf
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="checkAllNotifications">
                                <label class="form-check-label" for="checkAllNotifications">
                                    Allow All Notifications
                                </label>
                            </div>
                            <hr>
                            <div class="row g-3">
                                @foreach ($Notifications_groups as $group)
                                    <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-6 col-sm-12">
                                        <div class="notification-group bg-light bg-gradient p-2 h-100">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input group-checkbox"
                                                    id="group_{{ $loop->index }}"
                                                    {{ in_array($group, $uncheckedNotifications) ? '' : 'checked' }}>
                                                <label class="form-check-label fw-bold" for="group_{{ $loop->index }}">
                                                    {{ $group }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('custom-scripts')
        <script>
            $(document).ready(function() {
                const $checkAll = $('#checkAllNotifications');
                const $groups = $('.group-checkbox');
                const $form = $('#myForm');

                // ✅ Toggle all groups when "Allow All Notifications" is clicked
                $checkAll.on('change', function() {
                    const isChecked = $(this).is(':checked');
                    $groups.prop('checked', isChecked);
                });

                // ✅ When any group is toggled, update the "Allow All" checkbox state
                $groups.on('change', function() {
                    const totalGroups = $groups.length;
                    const checkedGroups = $groups.filter(':checked').length;
                    $checkAll.prop('checked', totalGroups === checkedGroups);
                });

                // ✅ Before submitting form, collect all checked groups into JSON
                $form.on('submit', function(e) {
                    // Prevent duplicate hidden inputs if re-submitting
                    $('input[name="notification_groups"]').remove();

                    const checkedGroups = [];
                    $groups.each(function() {
                        if (!$(this).is(':checked')) {
                            // Get the label text next to the checkbox
                            const groupName = $(this).closest('.form-check').find('label').text()
                                .trim();
                            checkedGroups.push(groupName);
                        }
                    });

                    // Convert to JSON and append as hidden field
                    const jsonGroups = JSON.stringify(checkedGroups);
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'notification_groups',
                        value: jsonGroups
                    }).appendTo($form);
                });

                // ✅ Initialize "Allow All" checkbox state correctly on page load
                (function init() {
                    const totalGroups = $groups.length;
                    const checkedGroups = $groups.filter(':checked').length;
                    $checkAll.prop('checked', totalGroups === checkedGroups);
                })();
            });
        </script>
    @endpush

@endsection
