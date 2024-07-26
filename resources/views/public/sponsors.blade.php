@extends('body-public')

@section('title', 'Sponsors')

@section('content')
  <main class="dk sponsors">

		<div class="title-section">
			<h1>Our Sponsors</h1>
			<h2>Thank you to all of our sponsors who have helped us to make this event possible</h2>
		</div>

		<div class="sponsors-main clear-box bg-white">
			@foreach ($records as $record)
				@if ($record->link)
					<a href="https://{{ $record->link }}" target="_blank">
						<img src="{{ env('AWS_ASSET_URL') . $record->fileName }}" alt="{{ $record->name }}" />
					</a>
				@else
					<div href="https://{{ $record->link }}" target="_blank" class="sponsor-non-link">
						<img src="{{ env('AWS_ASSET_URL') . $record->fileName }}" alt="{{ $record->name }}" />
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
