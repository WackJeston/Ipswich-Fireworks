@extends('body-public')

@section('content')
  <main class="home-page">

    @if ($errors->any())
      <div id="publicerror" class="lt floating">
        <publicerror :errormessages="{{ str_replace(array('[', ']'), '', $errors) }}" errorcount="{{ count($errors) }}" />
      </div>
    @endif

    @if (session()->has('message'))
			<div id="publicmessage" class="lt floating">
				<publicmessage successmessage="{{ session()->get('message') }}" />
      </div>
    @endif

		@if (count($landingZoneBanners) > 0)
			<div class="banner-zone">
				<div async id="bannerhometop">
					<bannerhometop 
						:banners="{{ json_encode($landingZoneBanners) }}"
						tickets="{{ $ticketsActive }}"
						ticketdate="{{ $ticketDate }}"
					/>
				</div>
	
				@if ($primaryInfo != null)
					<div class="primary-info-desktop">
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
			</div>
		@endif

		@if ($primaryInfo != null)
			<div class="primary-info-mobile clear-box limited dk">
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
		{{-- <div style="display: flex; flex-direction: column; align-items: center; margin: 30px 40px 50px 40px;">
			<img style="display flex; height: 500px;" src="{{env('ASSET_PATH')}}homepage-advert.jpeg" alt="Performance Act Advert">
		</div> --}}

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
