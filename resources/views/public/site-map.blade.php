@extends('layout')

@section('title', 'Site Map')

@section('content')
  <main class="dk site-map">

		<div class="title-section">
			<h1 class="page-margin">Site Map</h1>
		</div>

		<div class="clear-box bg-gray">
			<h2>Pages</h2>

			<ul>
				<li>
					<a href="/" class="page-link no-underline"><i class="fa-solid fa-house-chimney"></i>Home</a>
				</li>

				@foreach ($publicLinks as $link)
					<li>
						<a href="{{ $link['link'] }}" class="page-link no-underline"><i class="{{ $link['icon'] }}"></i>{{ ucwords($link['title']) }}</a>
					</li>
				@endforeach
			</ul>
		</div>

  </main>
@endsection
