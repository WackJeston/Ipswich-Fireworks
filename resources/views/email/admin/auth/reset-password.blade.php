@extends('email')

@section('title', 'New Enquiry')

@section('content')

	<img src="{{ env('APP_URL') . env('ASSET_PATH') }}logo-yellow.png" alt="Comapny Logo" height="80px" style="float: right;">
	<h2>Reset Password</h2>

	<p>
		You have recently requested that your password be reset. Please <a href="{{ env('APP_URL') }}/admin/reset-password/{{ $email }}/{{ $token }}" style="text-decoration: underline; color: #30aae4;">click here</a> to reset your password.
		<br>
		<br>
		If you did not request that your password be reset, then please ignore and delete this email.
	</p>
  
@endsection