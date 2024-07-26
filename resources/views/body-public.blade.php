@extends('layout')

@section('body-public')
	<div id="vuemenu">
		<vuemenu
			sitetitle="{{ env('APP_NAME') }}"
			publicasset="{{ env('ASSET_PATH') }}"
			:publiclinks="{{ json_encode($publicLinks) }}"
			:socials="{{ json_encode($socials) }}"
			:sessionuser="{{ auth()->user() }}"
		/>
	</div>

	<div id="page-container">
		<div id="vueheader">
			<vueheader
				sitetitle="{{ env('APP_NAME') }}"
				sitetitlemini="{{ env('APP_NAME_MINI') }}"
				publicasset="{{ env('ASSET_PATH') }}"
				:publiclinks="{{ json_encode($publicLinks) }}"

				:socials="{{ json_encode($socials) }}"
				:sessionuser="{{ auth()->user() }}"

			/>
		</div>

		@yield('content')

		@if (!session()->has('_previous'))
			<div id="loading-screen">
				<img async src="{{ env('ASSET_PATH') . 'website-logo.svg' }}" alt="logo" class="logo">
			</div>
		@endif

		<div id="vuefooter">
			<vuefooter
				sitetitle="{{ env('APP_NAME') }}"
				publicasset="{{ env('ASSET_PATH') }}"
				:publiclinks="{{ json_encode($publicLinks) }}"

				:socials="{{ json_encode($socials) }}"
				:sessionuser="{{ auth()->user() }}"
			/>
		</div>
	</div>
  
@endsection