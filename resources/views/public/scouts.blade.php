@extends('layout')

@section('title', '11th Ipswich Scout Group')

@section('content')
  <main class="dk scouts">

		<div class="title-section">
			<h1>11th Ipswich Scout Group</h1>
		</div>

    @if (session()->has('message'))
      <div id="publicmessage" class="lt floating">
        <publicmessage successmessage="{{ session()->get('message') }}" />
      </div>
    @endif

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
