@extends('backend.layouts.quick-booking')

@section('title') {{ __('messages.quick_booking') }} @endsection

@push('after-styles')
    <link rel="stylesheet" href='{{ mix("modules/quickbooking/style.css") }}'>
@endpush

@section('content')
  <div class="container">
    <div class="row justify-content-center align-items-center vh-100">
      <div class="col">
        <quick-booking :user_id="{{ $id }}"></quick-booking>
      </div>
    </div>
  </div>
@endsection

@push ('after-scripts')
<script src="{{ mix("modules/quickbooking/script.js") }}"></script>
@endpush
