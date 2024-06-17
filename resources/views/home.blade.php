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
		
		{{-- Remove after updates are complete --}}
		<div style="display: flex; flex-direction: column; align-items: center; margin: 0 40px 50px 40px;">
			<img style="display flex; height: 500px;" src="https://ipswich-fireworks.s3.eu-west-2.amazonaws.com/assets/homepage-banner-advert.jpeg" alt="Performance Act Advert">
		</div>

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
