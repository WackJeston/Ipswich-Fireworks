@extends('body-admin')

@section('title', 'Cron Jobs')

@section('content')
  <main class="cron-jobs">
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
			echo $cronJobs['html'];
		@endphp
  </main>
@endsection
