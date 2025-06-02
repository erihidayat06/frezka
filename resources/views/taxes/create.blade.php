@extends('backend.layouts.app')

@section('title')
    {{ __($module_action) }}
@endsection


@push('after-styles')
    <link rel="stylesheet" href="{{ mix('modules/constant/style.css') }}">
@endpush
@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
            <h4 id="form-offcanvasLabel" class="mb-0">
                {{ isset($tax) ? __('frontend.edit_tax') : __('frontend.create_tax') }}
            </h4>
            <a href="{{ route('backend.plan.tax.index') }}" class="btn btn-primary">{{ __('frontend.back') }}</a>
        </div>
        <form id="tax-form" enctype="multipart/form-data" method="POST" action="{{ route('backend.plan.tax.store') }}">
            @csrf
            <input type="hidden" name="id" value="{{ isset($tax) ? $tax->id : null }}">

            <div class="form-group">
                <label class="form-label" for="title">{{ __('frontend.title') }} <span class="text-danger">*</span></label>
                <input type="text" name="title" id="title" class="form-control" placeholder="{{ __('frontend.enter_title') }}" value="{{ isset($tax) ? $tax->title : '' }}">
                <span class="error text-danger"></span>
            </div>


            <div class="form-group">
                <label class="form-label" for="type">{{ __('frontend.select_type') }} <span class="text-danger">*</span></label>
                <div class="input-container">
                    <select class="form-control select2" id="type" name="type">
                        <option value="">{{ __('frontend.select_type') }}</option>
                        <option value="Percentage" {{ (isset($tax) && $tax->type == 'Percentage') ? 'selected' : '' }}>{{ __('frontend.percentage') }}</option>
                        <option value="Fixed" {{ (isset($tax) && $tax->type == 'Fixed') ? 'selected' : '' }}>{{ __('frontend.fixed') }}</option>
                    </select>
                </div>
                <span class="error text-danger"></span>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="value">{{ __('frontend.value') }} <span class="text-danger">*</span></label>
                <input type="number" name="value" id="value" class="form-control" placeholder="{{ __('frontend.enter_value') }}" value="{{ isset($tax) ? $tax->value : '' }}" min="0">
                <span class="error text-danger"></span>
            </div>

            

            <div class="form-group">
                <label class="form-label" for="plan_id">{{ __('frontend.plans') }} <span class="text-danger">*</span></label>
                <div class="input-container">
                    <select class="form-control" id="plan_id" name="plan_ids[]" multiple>
                        @foreach ($plans as $plan)
                            <option value="{{ $plan->id }}" {{ (isset($tax) && in_array($plan->id, $tax->plan_ids ? explode(",", $tax->plan_ids) : [])) ? 'selected' : '' }}>{{ $plan->name }}</option>
                        @endforeach
                    </select>
                </div>
                <span class="error text-danger"></span>
            </div>

            <div class="form-check form-switch">
                <label class="form-label">{{ __('frontend.status') }}</label>
                <input class="form-check-input" name="status" type="checkbox" {{ (isset($tax) && $tax->status == 0) ? '' : 'checked' }}>
            </div>

            <button type="submit" class="btn btn-primary mt-4">{{ __('frontend.submit') }}</button>
        </form>
    </div>
</div>
@endsection

@push('after-styles')
    <!-- DataTables Core and Extensions -->

    <link rel="stylesheet" href="{{ asset('vendor/datatable/datatables.min.css') }}">
@endpush

@push('after-scripts')
    <script>
      $(document).ready(function() {
    $('#plan_id').select2({
        placeholder: 'Select Plans',
        allowClear: true,
        width: '100%'
    });

    $("#tax-form").validate({
        rules: {
            title: { required: true },
            value: { required: true },
            type: { required: true },
            "plan_ids[]": { required: true },
        },
        errorElement: "span",
        errorClass: "error text-danger",
        highlight: function (element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid');
        },
        submitHandler: function (form) {
            $(form).find('.error').remove();
            $(form).trigger("submit");

        }
    });
});
    </script>
@endpush
