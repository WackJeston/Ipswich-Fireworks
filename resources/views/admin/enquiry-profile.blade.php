@extends('body-admin')

@if ($enquiry->type == 'standard')
	@section('title', 'Enquiry Details')
@elseif ($enquiry->type == 'feedback')
	@section('title', 'Feeback Enquiry Details')
@elseif ($enquiry->type == 'sponsor')
	@section('title', 'New Sponsor Enquiry Details')
@endif

@section('content')
  <main class="enquiry-profile">
		
    <div class="link-trail">
			<i class="fa-solid fa-arrow-left"></i>
			<a href="/admin/enquiries">Enquiries</a>
		</div>

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
					@if ($enquiry->type != 'sponsor')
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
