@extends('body-public')

@section('title', 'Map')

@section('content')
  <main class="dk map">

		<h1 class="page-margin">Map</h1>

		<div class="content">
			<div id="publicmap">
				<publicmap 
					:map="{{ json_encode($map) }}"
				/>
			</div>
		</div>

  </main>
@endsection
