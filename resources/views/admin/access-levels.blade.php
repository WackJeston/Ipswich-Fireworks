@extends('body-admin')

@section('title', 'Access Levels')

@section('content')
  <main class="access-levels">
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
			echo $form['html'];
			echo $table['html'];
		@endphp
  </main>
@endsection
