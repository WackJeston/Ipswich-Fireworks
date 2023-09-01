@extends('layout')

@section('title', 'Supporters')

@section('content')
  <main class="dk supporters">

		<h1 class="page-margin">Our Supporters</h1>

		<div class="clear-box bg-gray">
			<h3>Thank you to all of our supporters who have helped us to make this event possible.</h3>

			<ul>
				@foreach ($records as $record)
					<li>
						<a :href="$record->link">
							{{ $record->name }}
						</a>
					</li>
				@endforeach
			</ul>
			
		</div>

  </main>
@endsection
