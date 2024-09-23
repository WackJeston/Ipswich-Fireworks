<?php
namespace App\Http\Controllers\Admin;

use DB;
use Illuminate\Http\Request;
use App\Classes\AwsSecretsManager;
use App\Classes\DataTable;
use App\Classes\DataForm;
use App\Classes\AccessLevelCommon;
use Aws\SecretsManager\SecretsManagerClient;
use Aws\SecretsManager\Exception\SecretsManagerException;

class SecretsController extends AdminController
{
  public function show(string $secret = null)
  {
		if (!AccessLevelCommon::authorise()) {
			return back()->withErrors(['1' => 'Not Authorised']);
		}

		$editForm = null;
		$deploy = false;

		if (!is_null($secret)) {
			$secretInfo = AwsSecretsManager::getSecret($secret);

			// dd($secretInfo['value']);

			$editForm = new DataForm(request(), sprintf('/admin-secretsUpdate/%s', $secret));
			$editForm->setTitle($secretInfo['name']);
			$editForm->addInput('textarea', 'secret', '', $secretInfo['value'], null, null, false, null, ['rows="30" style="min-height: 400px;"']);
			$editForm = $editForm->render();

			if (str_contains($secretInfo['name'], '.env')) {
				if ((str_contains($_SERVER['DOCUMENT_ROOT'], '/dev') && $secretInfo['name'] == '.env.dev') || (!str_contains($_SERVER['DOCUMENT_ROOT'], '/dev') && $secretInfo['name'] == '.env')) {
					$deploy = true;
				}
			}
		}

		$secretsData = AwsSecretsManager::listSecrets();

		$secretsList = [];

		foreach ($secretsData as $i => $secretData) {
			$secretsList[] = ['value' => $secretData['ARN'], 'label' => $secretData['Name']];
		}

		$selectForm = new DataForm(request(), '/admin-secretsSelect/', 'Select');
		$selectForm->addInput('select', 'secret', 'Secret', $secretInfo['id'] ?? null);
		$selectForm->populateOptions('secret', $secretsList, false);
		$selectForm = $selectForm->render();

    return view('admin/secrets', compact(
			'editForm',
			'deploy',
			'selectForm'
		));
  }

	public function select(Request $request)
	{
		$secret = $request->input('secret');

		return redirect('/admin/secrets/' . $secret);
	}

	public function update(Request $request, string $secret)
	{
		AwsSecretsManager::updateSecret($secret, $request->input('secret'));

		return redirect('/admin/secrets/' . $secret, )->with('message', 'Secret has been updated.');
	}

	public function deployEnv() {
		$result = AwsSecretsManager::deployEnv();
		
		if($result['result']) {
			return redirect('/admin/secrets/' . $result['id'], )->with('message', $result['name'] . ' has been deployed.');
		} else {
			return redirect('/admin/secrets/' . $result['id'], )->with('error', 'Failed to deploy ' . $result['name'] . '.');
		}
	}
}
