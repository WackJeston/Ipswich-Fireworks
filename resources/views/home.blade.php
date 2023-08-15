@extends('layout')

@section('content')
  <main class="home-page">

    @if (session()->has('message'))
      <div id="publicmessage" class="lt floating">
        <publicmessage successmessage="{{ session()->get('message') }}" />
      </div>
    @endif

		@if (count($landingZoneBanners) > 0)
			<div id="bannerhometop">
				<bannerhometop 
					:banners="{{ json_encode($landingZoneBanners) }}"
					asset="{{ env('AWS_ASSET_URL') }}"
				/>
			</div>
		@endif

		@if ($primaryInfo != null)
			<div class="clear-box limited dk">
				@if ($primaryInfo->title != null)
					<h2>{{ $primaryInfo->title }}</h2>
				@endif
				@if ($primaryInfo->subtitle != null)
					<h3>{{ $primaryInfo->subtitle }}</h3>
				@endif
				@if ($primaryInfo->description != null)
					<p>{{ $primaryInfo->description }}</p>
				@endif
			</div>
		@endif

		@if ($ticketNotice != null)
			<div id="ticket-notice">
				<h2>{{ $ticketNotice->description }}</h2>
			</div>
		@endif

  </main>
@endsection
