@extends('layout')

@section('title', 'Find Us')

@section('content')
  <main class="dk find-us">

		<h1 class="page-margin">Find Us</h1>

		<div class="page-margin content">
			<h2>Ipswich's historic Christchurch Park can be found just a short walk from the town centre. Postcode: IP4 2BX</h2>
			<h3>The following gates will be open from 6pm...</h3>
			<ul>
				<li><strong>Soane Street</strong> - Very near to Ipswich town centre.</li>
				<li><strong>Fonnereau Road</strong> - The closest gate to Ipswich town centre and Ipswich bus station.</li>
				<li><strong>Bolton Lane</strong> - Not open for 2023 event.</li>
				<li><strong>Park Road</strong> - Walking distance from Henley Road/Valley Road traffic lights.</li>
				<li><strong>Westerfield Road</strong> - Easy access from Valley Road roundabout.</li>
			</ul>

			<h3>Address</h3>
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

    <div id="googlemaps">
      <googlemaps 
				:coordinates="{{ json_encode($coordinates) }}"
			/>
    </div>

  </main>
@endsection
