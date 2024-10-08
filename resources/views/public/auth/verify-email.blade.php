@extends('body-public')

@section('title', 'Verify Email')

@section('content')
  <main class="auth dk" id="verify-email-Page">

    @if ($errors->any())
      <div id="publicerror" class="lt limited">
        <publicerror :errormessages="{{ str_replace(array('[', ']'), '', $errors) }}" errorcount="{{ count($errors) }}" />
      </div>
    @endif

    @if (session()->has('message'))
      <div id="publicmessage" class="lt limited">
        <publicmessage successmessage="{{ session()->get('message') }}" />
      </div>
    @endif

    <div class="web-box limited dk">
      <h3>Please verify your email</h3>
      <p>A verification link has been sent to {{ $email }}. Please follow the link to verify your email and complete signing up.</p>
    </div>

    <div class="auth-nav">
      <p>Not recieved the email?</p>
      <a href="/verify-email-resend/{{ $id }}" class="page-button padding">Resend Email</a>
    </div>


  </main>
@endsection
