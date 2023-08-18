@extends('layout')

@if ($type == 'standard')
	@section('title', 'Enquiry Profile')
@elseif ($type == 'feedback')
	@section('title', 'Feeback Profile')
@endif

@section('content')
  <main class="enquiry-profile">

		<div class="link-trail">
      <i class="fa-solid fa-arrow-left"></i>
      <a href="/admin/enquiries">Enquiries</a>
    </div>

    @if ($type == 'standard')
			<h2 class="dk">Enquiry Profile</h2>
		@elseif ($type == 'feedback')
			<h2 class="dk">Feeback Profile</h2>
		@endif

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

		<ul class="web-box">
			<li><strong>Name:</strong> {{ $enquiry->name }}</li>
			<li><strong>Email:</strong> {{ $enquiry->email }}</li>
			<li><strong>Phone:</strong> {{ $enquiry->phone }}</li>
			<li><strong>Subject:</strong> {{ $enquiry->subject }}</li>
			<li><strong>Message:</strong></li>
			<li class="text-box">{{ $enquiry->message }}</li>
		</ul>

  </main>
@endsection
