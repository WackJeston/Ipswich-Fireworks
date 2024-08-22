@extends('body-admin')

@if ($type == 'standard')
	@section('title', 'Enquiry Details')
@elseif ($type == 'feedback')
	@section('title', 'Feeback Enquiry Details')
@elseif ($type == 'sponsor')
	@section('title', 'New Sponsor Enquiry Details')
@endif

@section('content')
  <main class="enquiry-profile">

    @if ($type == 'standard')
			<div class="link-trail">
				<i class="fa-solid fa-arrow-left"></i>
				<a href="/admin/enquiries">Enquiries</a>
			</div>
		@elseif ($type == 'feedback')
			<div class="link-trail">
				<i class="fa-solid fa-arrow-left"></i>
				<a href="/admin/feedback">Feedback</a>
			</div>
		@elseif ($type == 'sponsor')
			<div class="link-trail">
				<i class="fa-solid fa-arrow-left"></i>
				<a href="/admin/new-sponsors">Sponsor Enquiries</a>
			</div>
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

		<div class="page-column-container columns-2">
			<div class="page-column">
				<ul class="web-box profile-details">
					<li><strong>Name:</strong> {{ $enquiry->name }}</li>
					<li><strong>Email:</strong> {{ $enquiry->email }}</li>
					<li><strong>Phone:</strong> {{ $enquiry->phone }}</li>
					<li><strong>Date:</strong> {{ date('d/m/Y H:m:s', strtotime($enquiry->created_at)) }}</li>
					@if ($type != 'sponsor')
						<li><strong>Subject:</strong> {{ $enquiry->subject }}</li>
					@endif			
				</ul>

				<div class="web-box">
					<strong>Message:</strong>
					<p>
						{{ $enquiry->message }}
					</p>
				</div>
			</div>

			<div class="page-column">
				
			</div>
		</div>
  </main>
@endsection
