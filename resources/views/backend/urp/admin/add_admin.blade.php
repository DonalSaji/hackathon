@extends('backend.body.master')
@section('title', 'Add Users')
@section('content')
    <script src="{{ asset('assets/vendor/jquery-mask-plugin/jquery.mask.min.js') }}"></script>


    <style>
        .backPermissionsbtn2 {
            display: none;
        }

        @media(max-width:576px) {
            .backPermissionsbtn1 {
                display: none;
            }

            .backPermissionsbtn2 {
                display: block;
            }
        }
    </style>

    <div class="row mt-3">
        <div class="col-12" style="z-index:2;">
            <button type="button" class="btn btn-primary backPermissionsbtn1"
                onclick="window.location.href='{{ route('all.users') }}'">
                <span><i class="mdi mdi-arrow-left-thin"></i> Back to All Users </span>
            </button>
            <a href="{{ route('all.users') }}" class="backPermissionsbtn2"><i
                    class="mdi mdi-arrow-left-thin-circle-outline mdi-24px"></i></a>
        </div>
    </div>
    <div class="row" style="margin-top: -55px;">
        <div class="col-12 text-center">
            <div class="page-title-box">

                <h4 class="page-title">Add User </h4>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-sm-7 mx-auto">
            <div class="card widget-flat">
                <div class="card-body">
                    <form id="myForm" method="POST" action="{{ route('store.admin') }}" class="forms-sample">
                        @csrf
                        <div class="row">
                            <!-- Admin Name -->
                            <div class="col-md-6 mb-2">
                                <div class="col-12">
                                    <label class="form-label" for="name"> Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Enter Name" value="{{ old('name') }}">
                                    @error('name')
                                        <div class="text-danger mt-1 text-sm">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Admin Email -->
                            <div class="col-md-6 mb-2">
                                <div class="col-12">
                                    <label class="form-label" for="email"> Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="Enter Email" value="{{ old('email') }}">
                                    @error('email')
                                        <div class="text-danger mt-1 text-sm">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <!-- Admin Phone -->
                            <div class="col-md-6 mb-2">
                                <div class="col-12">
                                    <label class="form-label"> Phone</label>
                                    <input type="text" class="form-control" id="phone" name="phone"
                                        placeholder="Enter phone no" data-toggle="input-mask" data-mask-format="0000000000"
                                        value="{{ old('phone') }}">
                                    @error('phone')
                                        <div class="text-danger mt-1 text-sm">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>


                            <!-- Role Name -->
                            <div class="col-md-6 mb-2">
                                <div class="col-12">
                                    <label class="form-label" for="roles">Role Name</label>
                                    <select class="form-select" id="roles" name="roles">
                                        <option selected="" disabled="">Select Role</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('roles')
                                        <div class="text-danger mt-1 text-sm">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <!-- Admin Password -->
                            <div class="col-md-6 mb-2">
                                <div class="col-12">
                                    <label class="form-label" for="password"> Password</label>
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Enter Password">
                                    @error('password')
                                        <div class="text-danger mt-1 text-sm">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary me-2">Save User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('custom-scripts')
        <script>
            $(document).on('submit', '#myForm', function(e) {
                e.preventDefault();

                const form = $(this);
                const formData = form.serialize();
                const submitButton = form.find('button[type="submit"]');

                $('.text-danger').remove();

                submitButton.html(`
                 <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                  Saving User...
                `);
                submitButton.prop('disabled', true);

                $.ajax({
                    url: "{{ route('store.admin') }}",
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

                            submitButton.html('Save User');
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
                                    input.closest('.col-md-6').append(errorMessage);
                                } else {
                                    input.after(errorMessage);
                                }
                            }

                            submitButton.html('Save User');
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

                            submitButton.html('Save User');
                            submitButton.prop('disabled', false);
                        }
                    }
                });
            });
        </script>
    @endpush


@endsection
