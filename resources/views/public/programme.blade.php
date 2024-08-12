@extends('body-public')

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

		@if (count($music) > 0)
			<div class="clear-box">
				<h2>This year's musical line-up:</h2>

				<ul>
					@foreach ($music as $item)
						<li>
							<span>
								@if ($item->label)
									<strong class="first-strong">{{ $item->label }}</strong>
								@endif
				
								{{ $item->value }}

								@if ($item->stage || $item->time)
									<br>
								@endif
							</span>

							@if ($item->stage)
								<strong>Stage: </strong>{{ $item->stage }}
							@endif

							@if ($item->time)
								<strong><i class="fa-regular fa-clock"></i> </strong>{{ $item->time }}
							@endif

							@if ($item->link)
								<br>
								<a href="{{ $item->link }}" class="page-link" target="_blank">{{ $item->link }}</a>
							@endif
						</li>
						@endforeach
				</ul>
			</div>

			<div class="image-grid">
				@foreach ($images as $image)
					<img src="{{ $image->fileName }}" alt="{{ $image->value }}">
				@endforeach
			</div>
		@endif
  </main>
@endsection
