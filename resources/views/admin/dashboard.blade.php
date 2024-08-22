@extends('body-admin')

@section('title', 'Dashboard')

@section('content')
  <main class="dashboard">

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
					echo $enquiriesTable['html'];
				@endphp
			</div>
			<div class="page-column">
				@php
				
				@endphp
			</div>
		</div>

  </main>
@endsection
