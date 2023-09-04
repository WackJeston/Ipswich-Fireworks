@extends('layout')

@section('title', 'Programme')

@section('content')
  <main class="dk programme">

		<h1 class="page-margin">Programme</h1>

		<div class="clear-box bg-gray">
			<h2>What's On?</h2>
			<p>So much more than just East Anglia's Premier Musical Fireworks Display! Check out our line-up!</p>

			<ul>
				@foreach ($standard as $item)
					<li>
						@if ($item->label)
							<strong>{{ $item->label }}:</strong>
						@endif
						
						{{ $item->value }}
					</li>
				@endforeach
			</ul>
		</div>

		<div class="clear-box">
			<h2>This year's musical line-up:</h2>

			<ul>
				@foreach ($music as $item)
					<li>
						@if ($item->label)
							<strong>{{ $item->label }}</strong>
						@endif
		
						{{ $item->value }}
					</li>
				@endforeach
			</ul>
		</div>

  </main>
@endsection
