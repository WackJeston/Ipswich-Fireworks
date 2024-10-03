@extends('body-public')

@section('title', 'Map')

@section('content')
  <main class="dk map">

		<h1>Map</h1>
		<h4>Select an item to view infomation and event times.</h4>

		<div id="publicmap">
			<publicmap 
				:map="{{ json_encode($map) }}"
			/>
		</div>

  </main>
@endsection
