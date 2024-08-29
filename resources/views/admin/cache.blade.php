@extends('body-admin')

@section('title', 'Cache')

@section('content')
  <main class="cache">
    @if ($errors->any())
      <div id="alerterror" class="lt">
        <alerterror :errormessages="{{ str_replace(array('[', ']'), '', $errors) }}" errorcount="{{ count($errors) }}" />
      </div>
    @endif

    @if (session()->has('message'))
      <div id="alertmessage" class="lt">
        <alertmessage successmessage="{{ session()->get('message') }}" />
      </div>
    @endif

		<div class="web-box">
			<h4 class="no-margin">Manually Reload Cache</h4>
			<br>

			<div class="page-button-row">
				<a href="/settingsClearCache/public-page-home" class="page-button">Home</a>
				<a href="/settingsClearCache/public-page-shop" class="page-button">Shop</a>
			</div>
		</div>
  </main>
@endsection
