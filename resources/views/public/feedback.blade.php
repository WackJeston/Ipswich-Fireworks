@extends('body-public')

@section('title', 'Feedback')

@section('content')
  <main class="dk feedback">

		<div class="title-section">
			<h1>Feedback</h1>
			<p>
				Hope you enjoyed the fireworks display! Your experience matters to us.<br>
				Please take a moment to share any feedback you have, we would greatly appreciate it.<br>
				Your insights help us improve.
			</p>

			<p>Thanks for being a part of our event!</p>
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

    <div id="feedbackmain">
      <feedbackmain />
    </div>

		<a href="/contact" class="large-message-box">
			<h2>Contact Us</h2>
		</a>

  </main>
@endsection
