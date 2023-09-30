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
					tickets="{{ $ticketsActive }}"
					ticketdate="{{ $ticketDate }}"
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
			<h2>ONLINE TICKETS ONLY<br>TICKETS NOT AVAILABLE ANYWHERE ELSE<br>NO PAYMENT TAKEN AT GATES</h2>
		</div>

		@if (count($bottomBanners) > 0)
			<div async id="bannerhomebottom">
				<bannerhomebottom 
					:banners="{{ json_encode($bottomBanners) }}"
				/>
			</div>
		@endif
  </main>
@endsection
