@extends('layout')

@section('title', 'Supporters')

@section('content')
  <main class="dk supporters">

		<div class="title-section">
			<h1>Our Supporters</h1>
			<h2>Thank you to all of our supporters who have helped us to make this event possible</h2>
		</div>

		<div class="supporters-main clear-box bg-white">

			@foreach ($records as $record)
				<a href="{{ $record->link }}" target="_blank">
					<img src="{{ env('AWS_ASSET_URL') . $record->fileName }}" alt="{{ $record->name }}" />
					{{-- {{ $record->name }} --}}
				</a>
			@endforeach
			
		</div>

  </main>
@endsection
