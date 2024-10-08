@extends('body-admin')

@section('title', '11th Ipswich Scout Group')

@section('content')
  <main class="scouts">

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
			echo $aboutUsForm['html'];
		@endphp

  </main>
@endsection
