@extends('backend.body.master')
@section('title', 'Edit Roles')
@section('content')


    <style>
        .backRolesbtn2 {
            display: none;
        }

        @media(max-width:576px) {
            .backRolesbtn1 {
                display: none;
            }

            .backRolesbtn2 {
                display: block;
            }
        }
    </style>

    <div class="row mt-3">
        <div class="col-12" style="z-index:2;">
            <button type="button" class="btn btn-primary backRolesbtn1"
                onclick="window.location.href='{{ route('all.roles') }}'">
                <span><i class="mdi mdi-arrow-left-thin"></i> Back to Roles</span>
            </button>
            <a href="{{ route('all.roles') }}" class="backRolesbtn2"><i
                    class="mdi mdi-arrow-left-thin-circle-outline mdi-24px"></i></a>
        </div>
    </div>

    <div class="row" style="margin-top: -55px;">
        <div class="col-12 text-center">
            <div class="page-title-box">
                <h4 class="page-title">Edit Roles</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-8 mx-auto">
            <div class="card widget-flat">
                <div class="card-body">
                    <form id="rolesForm" action="{{ route('update.roles') }}" method="post">
                        @csrf

                        <input type="hidden" name="id" value="{{ $roles->id }}">

                        <!-- Roles Name -->
                        <div class="row mb-2">
                            <div class="col-12">
                                <label class="form-label" for="name">Roles Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Enter Roles Name" value="{{ $roles->name }}">

                                @error('name')
                                    <div class="text-danger mt-1 text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Save Changes Button -->
                        <div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('custom-scripts')
    <script>
        $(document).on('submit', '#rolesForm', function(e) {
            e.preventDefault();

            const form = $(this);
            const formData = form.serialize();
            const submitButton = form.find('button[type="submit"]');

            $('.text-danger').remove();

              submitButton.html(`
              <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                  Saving Role...
               `);

            submitButton.prop('disabled', true);

            $.ajax({
                url: "{{ route('update.roles') }}",
                type: 'POST',
                data: formData,
                success: function(response) {

                    if (response && response.status === 'success') {

                        sessionStorage.setItem('responseMessage', response.message);

                        window.location.href = response.redirect_url;

                    } else {

                        $.NotificationApp.send(
                            "Error",
                            response.error,
                            "top-right",
                            "rgba(0,0,0,0.2)",
                            "error"
                        );

                        submitButton.html('Save Changes');
                        submitButton.prop('disabled', false);
                    }
                },
                error: function(xhr, status, error) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        

                        for (let field in errors) {
                            const input = $(`[name="${field}"]`);

                            const errorMessage = '<div class="text-danger mt-1 text-sm">' + errors[
                                field][0] + '</div>';


                            if (input.is('select')) {
                                input.closest('.col-md-10').append(errorMessage);
                            } else {
                                input.after(errorMessage);
                            }
                        }

                        submitButton.html('Save Changes');
                        submitButton.prop('disabled', false);

                        return false;
                    } else {

                        $.NotificationApp.send(
                            "Error",
                            response.error,
                            "top-right",
                            "rgba(0,0,0,0.2)",
                            "error"
                        );

                        submitButton.html('Save Changes');
                        submitButton.prop('disabled', false);
                    }
                }
            });
        });
    </script>
@endpush


@endsection
