@extends('body-admin')

@section('title', 'Secrets Manager')

@section('content')
  <main class="secrets">
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
			echo $selectForm['html'];
		@endphp

		@php
			if (!is_null($editForm)) {
				echo $editForm['html'];
			}
		@endphp
  </main>
@endsection
