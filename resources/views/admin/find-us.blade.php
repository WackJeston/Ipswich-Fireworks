@extends('layout')

@section('title', 'Find Us')

@section('content')
  <main class="find-us">

    <h1>Find Us</h1>

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
			echo $gateForm['html'];
			echo $gateTable['html'];
		@endphp

  </main>
@endsection
