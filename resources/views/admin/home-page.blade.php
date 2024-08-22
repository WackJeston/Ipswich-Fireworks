@extends('body-admin')

@section('title', 'Home Page')

@section('content')
  <main class="home-page">

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

		@php
			echo $landingZoneBannerForm['html'];
			echo $landingZoneBannerTable['html'];
			echo $primaryInfoForm['html'];
			echo $bottomBannerForm['html'];
			echo $bottomBannerTable['html'];
		@endphp

  </main>
@endsection
