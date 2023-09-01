@extends('layout')

@section('title', 'Supporters')

@section('content')
  <main class="dk supporters">

		<h1>Our Supporters</h1>

		<div class="clear-box limited dk">
			<h2>Thank you to all of our supporters who have helped us to make this event possible</h2>

			<ul>
				@foreach ($records as $record)
					<li>
						<a href="{{ $record->link }}" target="_blank">
							<img src="{{ $record->fileName }}" alt="{{ $record->name }}" />
							{{ $record->name }}
						</a>
					</li>
				@endforeach
			</ul>
			
		</div>

  </main>
@endsection
