@extends('body-public')

@section('title', 'Contact')

@section('content')
  <main class="dk contact">

		<div class="title-section">
			<h1>Contact Us</h1>
			<p>The organizing team consists of a dedicated group of volunteers who are committed to promptly addressing your inquiries. Your satisfaction is our top priority, and we will make every effort to provide you with a swift and comprehensive response. Please feel free to reach out to us with any questions or concerns you may have.</p>
		</div>

		@if ($errors->any())
      <div id="alerterror" class="lt page-margin">
        <alerterror :errormessages="{{ str_replace(array('[', ']'), '', $errors) }}" errorcount="{{ count($errors) }}" />
      </div>
    @endif

    @if (session()->has('message'))
      <div id="publicmessage" class="lt floating">
        <publicmessage successmessage="{{ session()->get('message') }}" />
      </div>
    @endif

    <div id="contactmain">
      <contactmain 
				:address="{{ json_encode($address) }}"
				:contact="{{ json_encode($contact) }}"
			/>
    </div>

  </main>
@endsection
