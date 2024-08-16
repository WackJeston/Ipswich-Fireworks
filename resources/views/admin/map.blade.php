@extends('body-admin')

@section('title', 'Map')

@section('content')
  <main class="map">

    <h1>Map</h1>

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

		<div id="adminmap" class="dk">
      <adminmap
				:map="{{ json_encode($map) }}"
				:icons="{{ json_encode($icons) }}"
			/>
    </div>

		@php
			echo $mapForm['html'];
			echo $iconForm['html'];
			echo $mapAssetTable['html'];
		@endphp

  </main>
@endsection
