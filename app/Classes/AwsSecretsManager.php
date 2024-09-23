<?php
namespace App\Classes;

use Aws\SecretsManager\SecretsManagerClient;
use Aws\SecretsManager\Exception\SecretsManagerException;

class AwsSecretsManager {
	private static $client;
	public static $errors;

	private static function init(): bool {
		if(!self::initClient()) {
			return false;
		}

		return true;
	}

	private static function initClient(): bool {
		if(!isset(self::$client)) {
			try {
				self::$client = new SecretsManagerClient([
					'version' => 'latest',
					'region' =>  $_ENV['AWS_DEFAULT_REGION'],
					'profile' => 'default',
				]);

				return true;

			} catch(SecretsManagerException $e) {
				self::$errors[] = $e->getAwsErrorMessage();

			} catch(Exception $e) {
				self::$errors[] = $e->getMessage();
			}

			return false;
		}

		return true;
	}

	// TODO: move to Application which uses AWS SecretManager. Add support for Secret Provider, AWS/Google etc.
	public static function deployEnv() {
		$secretName = str_contains($_SERVER['DOCUMENT_ROOT'], '/dev') ? '.env.dev' : '.env';
		$secret = self::getSecret($secretName);

		if($secret !== false) {
			write(str_replace('public', '', $_SERVER['DOCUMENT_ROOT']) . '.env', $secret['value']);

			return [
				'result' => true,
				'id' => $secret['id'],
				'name' => $secretName
			];
		}

		return [
			'result' => false,
			'id' => $secret['id'],
			'name' => $secretName
		];
	}

	public static function getSecret(string $secretName, ?string $versionId = null) {
		self::$errors = [];

		if(self::init()) {
			try {
				$args = [
					'SecretId' => $secretName,
				];

				if(!is_null($versionId)) {
					$args['VersionId'] = $versionId;
				}

				$result = self::$client->getSecretValue($args);

				// Decrypts the secret using the associated KMS CMK, depends on whether the secret is a string or binary.
				if(isset($result['SecretString'])) {
					$secret = $result['SecretString'];
				} else {
					$secret = base64_decode($result['SecretBinary']);
				}

				return [
					'id' => $result['ARN'],
					'name' => $result['Name'],
					'value' => $secret,
				];

			} catch(SecretsManagerException $e) {
				self::$errors[] = $e->getAwsErrorMessage();

			} catch(Exception $e) {
				self::$errors[] = $e->getMessage();
			}
		}

		return false;
	}

	public static function listSecrets(int $limit = 100) {
		self::$errors = [];

		if(self::init()) {
			$nextToken = null;
			$results = [];

			try {
				do {
					$args = [
						'IncludePlannedDeletion' => true,
						'MaxResults' => $limit,
						'SortOrder' => 'asc',
					];

					if(!is_null($nextToken)) {
						$args['NextToken'] = $nextToken;
					}

					$result = self::$client->listSecrets($args);

					$nextToken = $result['NextToken'] ?? null;

					$results = array_merge($results, $result['SecretList']);

				} while(!is_null($nextToken));

				return $results;

			} catch(SecretsManagerException $e) {
				self::$errors[] = $e->getAwsErrorMessage();

			} catch(Exception $e) {
				self::$errors[] = $e->getMessage();
			}
		}

		return false;
	}

	public static function listVersions(string $secretName, bool $includeDeprecated = true, int $limit = 100) {
		self::$errors = [];

		if(self::init()) {
			$nextToken = null;
			$results = [];

			try {
				do {
					$args = [
						'SecretId' => $secretName,
						'IncludeDeprecated' => $includeDeprecated,
						'MaxResults' => $limit,
					];

					if(!is_null($nextToken)) {
						$args['NextToken'] = $nextToken;
					}

					$result = self::$client->listSecretVersionIds($args);

					$nextToken = $result['NextToken'] ?? null;

					$results = array_merge($results, $result['Versions']);

				} while(!is_null($nextToken));

				return $results;

			} catch(SecretsManagerException $e) {
				self::$errors[] = $e->getAwsErrorMessage();

			} catch(Exception $e) {
				self::$errors[] = $e->getMessage();
			}
		}

		return false;
	}

	public static function getCurrentVersion(string $secretName) {
		self::$errors = [];

		if(self::init()) {
			$results = self::listVersions($secretName, false);

			foreach($results as $result) {
				if(in_array('AWSCURRENT', $result['VersionStages'])) {
					return $result;
				}
			}
		}

		return false;
	}

	public static function updateSecret(string $secretName, string $value) {
		self::$errors = [];

		if(self::init()) {
			try {
				$args = [
					'SecretId' => $secretName,
					'SecretString' => $value,
				];

				$result = self::$client->updateSecret($args);

				return $result;

			} catch(SecretsManagerException $e) {
				self::$errors[] = $e->getAwsErrorMessage();

			} catch(Exception $e) {
				self::$errors[] = $e->getMessage();
			}
		}

		return false;
	}

	public static function updateStage(string $secretName, string $versionId, string $oldVersionId, string $stage = 'AWSCURRENT') {
		self::$errors = [];

		if(self::init()) {
			try {
				$args = [
					'SecretId' => $secretName,
					'VersionStage' => $stage,
					'MoveToVersionId' => $versionId,
					'RemoveFromVersionId' => $oldVersionId,
				];

				$result = self::$client->updateSecretVersionStage($args);

				return $result;

			} catch(SecretsManagerException $e) {
				self::$errors[] = $e->getAwsErrorMessage();

			} catch(Exception $e) {
				self::$errors[] = $e->getMessage();
			}
		}

		return false;
	}
}