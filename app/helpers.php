<?php

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Aws\Ses\SesClient;
use KlaviyoAPI\KlaviyoAPI;

use App\Models\Asset;
use App\Models\Products;
use App\Models\User;

function write($filePath, $data = '', $mode = 'w') {
	$fh = fopen($filePath, $mode);

	if($fh) {
		fwrite($fh, $data);
		fclose($fh);

		return $filePath;
	}

	return false;
}

function resetShowMarker() {
	if ((empty(session()->get('_previous')['url']) && empty(session()->get('pageShowMarkerPrevious')[0])) || !in_array(explode('?', url()->current())[0], [explode('?', session()->get('pageShowMarkerPrevious'))[0], explode('?', session()->get('_previous')['url'])[0]])) {
		session()->put('pageShowMarker', false);
	}
}

// Klaviyo
function subscribeKlaviyo($userId) {
	try {
		$user = User::find($userId);

		$klaviyo = new KlaviyoAPI(env('KLAVIYO_PRIVATE_KEY'));
		
		$response = $klaviyo->Profiles->createOrUpdateProfile([
			'data' => [
				'type' => 'profile',
				'attributes' => [
					'email' => $user->email,
					// 'external_id', $user->id,
					'first_name' => $user->firstname,
					'last_name' => $user->lastname,
				],
			]
		]);

	} catch (\Throwable $th) {
		dd($th);
		return false;

	} finally {
		$user->klaviyoId = $response['data']['id'];
		$user->save();

		try {
			$response2 = $klaviyo->Profiles->subscribeProfiles([
				'data' => [
					'type' => 'profile-subscription-bulk-create-job',
					'attributes' => [
						'custom_source' => 'Website Sign Up',
						'profiles' => [
							'data' => [
								[
									'type' => 'profile',
									'id' => $user->klaviyoId,
									'attributes' => [
										'email' => $user->email,
										'subscriptions' => [
											'email' => [
												'marketing' => [
													'consent' => 'SUBSCRIBED',
												]
											],
										],
									],
								]
							],
						],
					],
					'relationships' => [
						'list' => [
							'data' => [
								'type' => 'list',
								'id' => env('KLAVIYO_LIST_ID'),
							],
						],
					],
				]
			]);

		} catch (\Throwable $th) {
			// dd($th);
			return false;
		}
	}
}


// AWS S3
function connectSes() {
  $connection = new SesClient([
    'version' => 'latest',
    'region' => $_ENV['AWS_DEFAULT_REGION'],
    'profile' => 'default',
  ]);

  return $connection;
}

