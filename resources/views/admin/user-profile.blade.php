@extends('body-admin')

@section('title', 'User Profile')

@section('content')
  <main class="user-profile">
		
		@if ($authorised)
			<div class="link-trail">
				<i class="fa-solid fa-arrow-left"></i>
				<a href="/admin/users">Users</a>
			</div>
		@endif

    @if ($errors->any())
      <div id="alerterror" class="lt">
        {{-- <alerterror :errormessages="{{ str_replace(array('[', ']'), '', $errors) }}" errorcount="{{ count($errors) }}" /> --}}
      </div>
    @endif

    @if (session()->has('message'))
      <div id="alertmessage" class="lt">
        <alertmessage successmessage="{{ session()->get('message') }}" />
      </div>
    @endif

    <div id="userprofilefunctions" class="dk">
      <userprofilefunctions
				pageshowmarker="{{ session()->get('pageShowMarker') }}"
				:user="{{ json_encode($user) }}" 
				:editform="{{ json_encode($editForm) }}"
			/>
    </div>

		<div class="page-column-container columns-2">
			<div class="page-column">
				<ul class="web-box profile-details">
					<li><strong>Name: </strong>{{ $user->firstName }} {{ $user->lastName }}</li>
					<li><strong>Email: </strong>{{ $user->email }}</li>
					<li><strong>Access Level: </strong>{{ $user->accessLevel }}</li>
					@if (!is_null($user->klaviyoId))
						<li><strong>Klaviyo ID: </strong>{{ $user->klaviyoId }}</li>
					@endif
				</ul>
			</div>

			<div class="page-column grid">
				@if (!empty($billingAddress))
					<div class="web-box shrink">
						<strong>Billing Address:</strong>
						<ul>
							<li>{{ $billingAddress->firstName }} {{ $billingAddress->lastName }}</li>
							<li>{{ $billingAddress->line1 }}</li>
							@if ($billingAddress->line2)
								<li>{{ $billingAddress->line2 }}</li>
							@endif
							@if ($billingAddress->line3)
								<li>{{ $billingAddress->line3 }}</li>
							@endif
							<li>{{ $billingAddress->city }}</li>
							<li>{{ $billingAddress->region }}, {{ $billingAddress->country }}</li>
							<li>{{ $billingAddress->postCode }}</li>
							
							@if ($billingAddress->phone)
								<li>{{ $billingAddress->phone }}</li>
							@endif
							@if ($billingAddress->email)
								<li>{{ $billingAddress->email }}</li>
							@endif
						</ul>
					</div>
				@endif
			</div>
		</div>
  </main>
@endsection
