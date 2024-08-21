@extends('body-admin-login')

@section('title', 'Reset Password')

@section('content')
  <main class="auth" id="adminResetPassword">

    <div id="adminresetpassword">
      <adminresetpassword 
				email="{{ $email }}"
				token="{{ $token }}"
			/>
    </div>

    @if ($errors->any())
      <div id="alerterror" class="lt">
        <alerterror :errormessages="{{ str_replace(array('[', ']'), '', $errors) }}" errorcount="{{ count($errors) }}" />
      </div>
    @endif

    @if (session()->has('message'))
      <div id="alertmessage" class="lt">
        <alertmessage successmessage="{{ session()->get('message') }}" />
      </div>
    @endif

    <h2 class="dk">{{ strtoupper(env('APP_NAME')) }}</h2>

  </main>
@endsection
