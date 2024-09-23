@extends('body-admin')

@section('title', 'Settings')

@section('content')
  <main class="settings">
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

		<div class="page-column-container columns-2">
			<div class="page-column">
				@php
					echo $form['html'];
				@endphp
			</div>
			<div class="page-column">
				
			</div>
		</div>
  </main>
@endsection
