@extends('layout')

@section('title', 'Find Us')

@section('content')
  <main class="dk find-us">

		<h1 class="page-margin">Find Us</h1>

		<div class="white-box">
			<div class="page-margin content">
				<h2>Ipswich's historic Christchurch Park can be found just a short walk from the town centre. Postcode: IP4 2BX</h2>
				<h3>The following gates will be open from 6pm...</h3>
				<ul>
					@foreach ($gates as $gate)
						<li><strong>{{ $gate->title }}</strong> - {{ $gate->description }}</li>
						<li class="gate-second-row">
							@if ($gate->active)
								<span class="gate-open">OPEN</span>
							@else
								<span class="gate-closed">CLOSED</span>
							@endif
							<a href="https://what3words.com/{{ $gate->subtitle }}" class="page-link">what3words</a>
						</li>
					@endforeach
				</ul>
	
				<h2>Event Address</h2>
				<ul class="address">
					@if (isset($contact['line1']))
						<li>{{ $contact['line1'] }}</li>
					@endif
					@if (isset($contact['line2']))
						<li>{{ $contact['line2'] }}</li>
					@endif
					@if (isset($contact['line3']))
						<li>{{ $contact['line3'] }}</li>
					@endif
					@if (isset($contact['city']))
						<li>{{ $contact['city'] }}</li>
					@endif
					@if (isset($contact['region']))
						<li>{{ $contact['region'] }}</li>
					@endif
					@if (isset($contact['country']))
						<li>{{ $contact['country'] }}</li>
					@endif
					@if (isset($contact['postcode']))
						<li>{{ $contact['postcode'] }}</li>
					@endif
				</ul>
			</div>
		</div>

    <div id="googlemaps">
      <googlemaps 
				:coordinates="{{ json_encode($coordinates) }}"
			/>
    </div>

		<div class="page-margin content">
			<h4>Please note there is no public car parking at this event. Please use public transport or town centre car parks. Limited disabled vehicle parking is available on site and can be booked when purchasing your entrance ticket online only</h4>
		</div>
  </main>
@endsection
