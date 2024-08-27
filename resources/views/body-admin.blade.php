@extends('layout')

@section('body-admin')

	@php
		$settingsPre = DB::select('SELECT
			n.id,
			n.group,
			n.name,
			COALESCE(nu.id, 0) AS notificationUserId,
			IF(nu.standard, 1, 0) AS `standard`,
			IF(nu.email, 1, 0) AS email
			FROM notification AS n
			LEFT JOIN notification_user AS nu ON nu.notificationId=n.id AND nu.userId = ?
			GROUP BY n.id', 
			[auth()->user()->id]
		);

		$settings = [];

		foreach ($settingsPre as $i => $settingPre) {
			$settings[$settingPre->group][] = $settingPre;
		}

		$showHome = str_contains(url()->current(), '/dashboard');

		$newNotification = session()->get('newNotification', 0);
		$notificationCount = session()->get('notificationCount', 0);
	@endphp

	<div id="admin-container">
		<div id="adminheader">
			<Adminheader
				sitetitle="{{ env('APP_NAME') }}"
				:adminlinks="{{ json_encode($adminLinks) }}"
				showHome="{{ json_encode($showHome) }}"
				:sessionuser="{{ auth()->user() }}"
				:settings="{{ json_encode($settings) }}"
				newnotification="{{ $newNotification }}"
				previousnotificationcount="{{ json_encode($notificationCount) }}"
			/>
		</div>

		<div class="sub-header">
			<h1>@yield('title')</h1>
		</div>

		@yield('content')
	</div>
  
@endsection