@extends('layout')

@section('content')
  <main class="lt home-page">

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

		@if ($content1 != null)
		<div class="clear-box limited dk">
			@if ($content1->title != null)
				<h2>{{ $content1->title }}</h2>
			@endif
			@if ($content1->subtitle != null)
				<h3>{{ $content1->subtitle }}</h3>
			@endif
			@if ($content1->description != null)
				<p>{{ $content1->description }}</p>
			@endif
    </div>
		@endif

  </main>
@endsection
