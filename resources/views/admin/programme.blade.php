@extends('body-admin')

@section('title', 'Programme')

@section('content')
  <main class="programme">

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
			echo $standardForm['html'];
			echo $standardTable['html'];
			echo $musicForm['html'];
			echo $musicTable['html'];
		@endphp

  </main>
@endsection
