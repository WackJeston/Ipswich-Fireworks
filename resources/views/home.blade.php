@extends('layout')

@section('content')
  <main class="home-page">

    @if (session()->has('message'))
      <div id="publicmessage" class="lt floating">
        <publicmessage successmessage="{{ session()->get('message') }}" />
      </div>
    @endif

		@if (count($landingZoneBanners) > 0)
			<div async id="bannerhometop">
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

		<div class="large-message-box gray-box limited">
			<h2>ONLINE TICKETS ONLY<br>TICKETS NOT AVAILABLE ANYWHERE ELSE<br>NO CASH TAKEN AT GATES</h2>
		</div>

		<div class="clear-box limited dk">
			@if ($aboutUs[0]->title != null)
				<h2>{{ $aboutUs[0]->title }}</h2>
			@endif
			@if ($aboutUs[0]->description != null)
				<p>{{ $aboutUs[0]->description }}</p>
			@endif
			@if ($aboutUs[1]->title != null)
				<h2>{{ $aboutUs[1]->title }}</h2>
			@endif
			@if ($aboutUs[1]->description != null)
				<p>{{ $aboutUs[1]->description }}</p>
			@endif
			@if ($aboutUs[2]->title != null)
				<h2>{{ $aboutUs[2]->title }}</h2>
			@endif
			@if ($aboutUs[2]->description != null)
				<p>{{ $aboutUs[2]->description }}</p>
			@endif
		</div>

  </main>
@endsection
