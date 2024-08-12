@extends('body-public')

@section('title', 'Supporters & Sponsors')

@section('content')
  <main class="dk supporters">

		<div class="title-section">
			<h1>Our Supporters & Sponsors</h1>
			<h2>Thank you to all of our supporters & sponsors who have helped us to make this event possible</h2>
		</div>

		<div class="supporters-main clear-box bg-white">
			@foreach ($records as $record)
				@if ($record->link)
					<a href="https://{{ $record->link }}" target="_blank">
						<img src="{{ $record->fileName }}" alt="{{ $record->name }}" />
					</a>
				@else
					<div href="https://{{ $record->link }}" target="_blank" class="supporter-non-link">
						<img src="{{ $record->fileName }}" alt="{{ $record->name }}" />
					</div>
				@endif
			@endforeach
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

    <div id="sponsorform">
      <sponsorform />
    </div>

  </main>
@endsection
