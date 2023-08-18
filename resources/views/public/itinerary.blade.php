@extends('layout')

@section('title', 'Itinerary')

@section('content')
  <main class="dk itinerary">

		<h1 class="page-margin">Itinerary</h1>

		<div class="clear-box bg-gray">
			<h2>What's On?</h2>
			<p>So much more than just East Anglia's Premier Musical Fireworks Display! Check out our line-up!</p>

			<ul>
				@foreach ($standard as $item)
					<li>
						@if ($item->label)
							<strong>{{ $item->label }}:</strong><br>
						@endif
						
						{{ $item->value }}
					</li>
				@endforeach
			</ul>
		</div>

		<div class="clear-box">
			<h3>This year's musical line-up:</h3>

			<ul>
				@foreach ($music as $item)
					@if ($item->label)
						<li><strong>{{ $item->label }}</strong></li>
					@endif
	
					<li>{{ $item->value }}</li>
				@endforeach
			</ul>
		</div>

  </main>
@endsection
