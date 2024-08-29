<?php
namespace App\Http\Controllers\Admin;

use DB;
use Illuminate\Http\Request;
use App\Classes\AwsSecretsManager;
use App\Classes\DataTable;
use App\Classes\DataForm;
use Aws\SecretsManager\SecretsManagerClient;
use Aws\SecretsManager\Exception\SecretsManagerException;

class SecretsController extends AdminController
{
  public function show(string $secret = null)
  {
		$editForm = null;

		if (!is_null($secret)) {
			$secretInfo = AwsSecretsManager::getSecret($secret);

			// dd($secretInfo['value']);

			$editForm = new DataForm(request(), '/admin-secretsUpdate/', 'Update');
			$editForm->setTitle($secretInfo['name']);
			$editForm->addInput('textarea', 'secret', '', $secretInfo['value'], null, null, false, null, ['style="min-height: 400px;"']);
			$editForm = $editForm->render();
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
			'selectForm'
		));
  }

	public function select(Request $request)
	{
		$secret = $request->input('secret');

		return redirect('/admin/secrets/' . $secret);
	}

	public function update(Request $request)
	{
		$secret = $request->input('secret');

		$connection = new SecretsManagerClient([
			'version' => 'latest',
			'region' => $_ENV['AWS_DEFAULT_REGION'],
			'profile' => 'default',
		]);

		$args = [
			'SecretId' => $secret,
		];

		try {
			$secretData = $connection->getSecretValue($args);
		} catch (\Throwable $th) {
			throw $th;
		}

		$secretString = $secretData['SecretString'];

		$editForm = new DataForm(request(), '/admin-secretsUpdate/', 'Update');
		$editForm->addInput('textarea', 'secret', 'Secret', $secretString);
		$editForm = $editForm->render();

		return view('admin/secrets', compact(
			'selectForm',
			'editForm'
		));
	}
}
