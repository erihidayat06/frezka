@extends('installer::backend.layouts.master')

@section('template_title')
    {{ trans('installer_messages.final.templateTitle') }}
@endsection

@section('title')
    <i class="fa fa-flag-checkered fa-fw" aria-hidden="true"></i>
    {{ trans('installer_messages.final.title') }}
@endsection


@section('container')

<h6 class="setup-title">Admin Login Credentials</h6>
<p class="text-center">Email: <b>superadmin@salon.com</b></p>
<p class="text-center">Password: <b>12345678</b></p>

 <div class="setup-actions">

    <div class="buttons">
        <a href="{{ route('index') }}" class="button intsaller-btn" id="exit-button" onclick="disableButton()">
            {{ trans('installer_messages.final.user_website') }}
        </a>
    </div>



 </div>

    <script>
        function disableButton() {
            document.getElementById('exit-admin-button').classList.add('disabled');
            document.getElementById('exit-button').classList.add('disabled');
            document.getElementById('exit-button').innerText = 'Loading...';
        }

        function  disableAdminButton() {

            document.getElementById('exit-button').classList.add('disabled');

           document.getElementById('exit-admin-button').classList.add('disabled');
           document.getElementById('exit-admin-button').innerText = 'Loading...';
        }




    </script>


@endsection
