@extends('backend.layouts.app')

@section('title')
    {{ $module_action }}
@endsection

@push('after-styles')
    <link rel="stylesheet" href="{{ mix('modules/constant/style.css') }}">
    <link rel="stylesheet" href="{{ mix('css/intlTelInput.css') }}">
@endpush

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap mb-3">
                <h4 id="form-offcanvasLabel" class="mb-0">
                    {{ isset($user) ? __('messages.edit') . ' ' . __('messages.admin') : __('messages.create') . ' ' . __('messages.admin') }}
                </h4>
                <a href="{{ route('backend.users.index') }}" class="btn btn-primary">{{ __('messages.back') }}</a>
            </div>

            <form id="user-form" enctype="multipart/form-data" method="POST" action="{{ route('backend.users.store') }}">
                @csrf
                <input type="hidden" name="id" value="{{ isset($user) ? $user->id : null }}">

                <div class="row align-items-center">
                    <div class="form-group col-md-6">
                        <label class="form-label" for="first_name">{{__('messages.lbl_first_name')}} <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" id="first_name" class="form-control" placeholder="{{__('profile.enter_first_name')}}" value="{{ old('first_name', $user->first_name ?? '') }}">
                        <span class="error text-danger">@error('first_name'){{ $message }}@enderror</span>
                    </div>

                    <div class="form-group col-md-6">
                        <label class="form-label" for="last_name">{{__('messages.lbl_last_name')}}<span class="text-danger">*</span></label>
                        <input type="text" name="last_name" id="last_name" class="form-control" placeholder="{{__('profile.enter_last_name')}}" value="{{ old('last_name', $user->last_name ?? '') }}">
                        <span class="error text-danger"></span>
                    </div>

                    <div class="form-group col-md-6">
                        <label class="form-label" for="email">{{__('messages.lbl_email')}} <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="{{__('profile.enter_email')}}" value="{{ old('email', $user->email ?? '') }}">
                        <span class="error text-danger">@error('email'){{ $message }}@enderror</span>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="mobile" class="form-label">{{ __('messages.mobile') }} <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="tel" id="mobile" name="mobile" class="form-control" placeholder="{{ __('messages.placeholder_phone') }}" value="{{ old('mobile', $user->mobile ?? '') }}">
                        </div>
                        <div id="mobile-error" class="error text-danger" style="display: none;">{{ __('messages.contact_required') }}</div>
                    </div>

                    @if (!isset($user))
                        <div class="form-group col-md-6">
                            <label class="form-label" for="password">{{__('messages.lbl_password')}} <span class="text-danger">*</span></label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="{{__('messages.enter_password')}}">
                            <span class="error text-danger"></span>
                            <div class="invalid-feedback" id="password_length_error" style="display: none;">{{ __('messages.password_must_be_between_8_and_12_characters') }}</div>
                        </div>

                        <div class="form-group col-md-6">
                            <label class="form-label" for="password_confirmation">{{__('messages.confirm_password')}} <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="{{__('messages.enter_confirm_password')}}">
                            <span class="error text-danger"></span>
                        </div>
                    @endif

                    <div class="form-group col-md-6">
                        <label class="w-100 form-label">{{__('messages.lbl_gender')}}<span class="text-danger">*</span></label>
                        <div class="d-flex align-items-center gap-3 form-control">
                            <div class="form-check form-check-inline d-flex align-items-center gap-1">
                                <input class="form-check-input" type="radio" name="gender" id="gender_male" value="male" {{ (!isset($user) || $user->gender == 'male') ? 'checked' : '' }}>
                                <label class="form-check-label" for="gender_male">{{__('messages.male')}}</label>
                            </div>
                            <div class="form-check form-check-inline d-flex align-items-center gap-1">
                                <input class="form-check-input" type="radio" name="gender" id="gender_female" value="female" {{ (isset($user) && $user->gender == 'female') ? 'checked' : '' }}>
                                <label class="form-check-label" for="gender_female">{{__('messages.female')}}</label>
                            </div>
                            <div class="form-check form-check-inline d-flex align-items-center gap-1">
                                <input class="form-check-input" type="radio" name="gender" id="gender_other" value="other" {{ (isset($user) && $user->gender == 'other') ? 'checked' : '' }}>
                                <label class="form-check-label" for="gender_other">{{__('messages.intersex')}}</label>
                            </div>
                        </div>
                        <p class="mb-0 error text-danger"></p>
                    </div>

                    <div class="form-group col-md-6">
                        <label class="form-label">{{__('messages.status')}}</label>
                        <div class="form-group form-check form-switch form-control d-flex align-items-center justify-content-between gap-3">
                            <label class="form-label" id="statusLabel">
                                {{ (isset($user) && $user->status == 0) ? __('messages.inactive') : __('messages.active') }}
                            </label>
                            <input class="form-check-input" name="status" type="checkbox" id="statusSwitch" onchange="toggleStatusLabel()" {{ (!isset($user) || $user->status == 1) ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>

                <!-- Submit Button with Spinner -->
                <button type="submit" id="submitBtn" class="btn btn-primary mt-4 d-inline-flex align-items-center gap-2">
                    <span id="submitSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    <span id="submitText">{{ __('messages.submit') }}</span>
                </button>

            </form>
        </div>
    </div>
@endsection

@push('after-scripts')
<script src="{{ mix('js/jquery.validate.min.js') }}"></script>
<script src="{{ mix('js/intlTelInput.min.js') }}"></script>
<script src="{{ mix('js/utils.js') }}"></script>

<script>
    $(document).ready(function () {
        var iti = window.intlTelInput(document.querySelector("#mobile"), {
            initialCountry: "in",
            separateDialCode: true,
            utilsScript: "{{ mix('js/utils.js') }}"
        });

        $('#mobile').on('input', function () {
            if (!iti.isValidNumber()) {
                $('#mobile').addClass('is-invalid');
                $('#mobile-error').text('Please enter a valid mobile number').show();
                $('#mobile-error').addClass('text-danger');
            } else {
                $('#mobile').removeClass('is-invalid');
                $('#mobile-error').hide();
            }
        });

        $("#user-form").validate({
            rules: {
                first_name: { required: true, minlength: 2, maxlength: 50 },
                last_name: { required: true, minlength: 2, maxlength: 50 },
                email: { required: true, email: true },
                password: {
                    required: function () {
                        return $("input[name='id']").val() === "";
                    },
                    minlength: 8,
                    maxlength: 14
                },
                password_confirmation: {
                    required: function () {
                        return $("input[name='id']").val() === "";
                    },
                    equalTo: "#password"
                },
                gender: { required: true }
            },
            messages: {
                password: {
                    required: "Password is required.",
                    minlength: "Password must be 8 to 14 characters.",
                    maxlength: "Password must be 8 to 14 characters."
                },
                password_confirmation: {
                    required: "Confirm password is required.",
                    equalTo: "Passwords do not match."
                },
                first_name: {
                    required: "First name is required."
                },
                last_name: {
                    required: "Last name is required."
                },
                email: {
                    required: "Email is required.",
                    email: "Please enter a valid email address."
                },
                gender: {
                    required: "Please select a gender."
                }
            },
            errorClass: "text-danger",
            errorElement: "div",
            errorPlacement: function (error, element) {
                if (element.attr("name") === "gender") {
                    error.insertAfter(element.closest('.form-control'));
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function (element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element) {
                $(element).removeClass('is-invalid');
            },
            submitHandler: function (form) {
                $('#submitSpinner').removeClass('d-none');
                $('#submitText').text("{{ __('messages.loadingRecords') }}...");
                $('#submitBtn').prop('disabled', true);
                form.submit();
            }
        });

        let isSubmitting = false;

        $('#user-form').on('submit', function (e) {
            if (!iti.isValidNumber()) {
                e.preventDefault();
                $('#mobile').addClass('is-invalid');
                $('#mobile-error').addClass('text-danger');
                $('#mobile-error').text('Please enter a valid mobile number.').show();
                return;
            }

            if (!isSubmitting) {
                isSubmitting = true;
                $('#submitSpinner').removeClass('d-none');
                $('#submitText').text("{{ __('messages.loadingRecords') }}...");
                $('#submitBtn').prop('disabled', true);
            }
        });

        $('#email').blur(function () {
            checkField('email', $(this).val());
        });

        $('#mobile').blur(function () {
            checkField('mobile', $(this).val());
        });

        function checkField(field, value) {
            $.ajax({
                url: "{{ route('backend.users.check_unique') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    field: field,
                    value: value
                },
                success: function (response) {
                    if (response.exists) {
                        $('#' + field).addClass('is-invalid text-danger');
                        $('#' + field + '-error').text(response.message).addClass('text-danger').show();
                    } else {
                        $('#' + field).removeClass('is-invalid text-danger');
                        $('#' + field + '-error').removeClass('text-danger').hide();
                    }
                }
            });
        }

        window.toggleStatusLabel = function () {
            const label = document.getElementById('statusLabel');
            label.textContent = document.getElementById('statusSwitch').checked
                ? '{{ __("messages.active") }}'
                : '{{ __("messages.inactive") }}';
        }
    });
</script>

<style>
    .text-danger {
        color: #dc3545 !important;
        font-size: 0.875rem;
    }
</style>
@endpush
