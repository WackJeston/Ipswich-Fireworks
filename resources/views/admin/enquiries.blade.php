@extends('layout')

@section('title', 'Enquiries')

@section('content')
  <main class="enquiries">

    <h2 class="dk">Enquiries</h2>

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
			echo $enquiriesTable['html'];
		@endphp

  </main>
@endsection
