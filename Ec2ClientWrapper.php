<?php

require_once(__DIR__ ."/./vendor/autoload.php");	// Path to AWS SDK for PHPのVersion 3.
use Aws\Ec2\Ec2Client;


/*
	Wrapper class of Ec2Client from AWS SDK for PHP.
	- author: kentaro-a
	- created: 2017-02-21
	- required:
		- AWS SDK 3.x.
		- PHP version 7.x.
	- references:
		- docs:
			http://docs.aws.amazon.com/aws-sdk-php/v3/api/
		- credentials:
			https://console.aws.amazon.com/iam/home?#users
*/
class Ec2ClientWrapper {
	
	const AWS_KEY =  "xxxxxxxxxxxxxxxxx";	// Your credentials api-key.
	const AWS_SECRET_KEY =  "xxxxxxxxxxxxxx";	// Your credentials api-key-secret.
	const AWS_REGION = "xxxxxxxxxx";	// Your region
	const AWS_VERSION = "latest";
	const AWS_META_ENDPOINT = "http://169.254.169.254/latest/meta-data/";

	private $client;

	/*
		Constructor.
	*/
	public function __construct() {
		$this->client = new Ec2Client([
							'version'=>self::AWS_VERSION,
							'credentials' => [
									'key'=>self::AWS_KEY,
									'secret'=>self::AWS_SECRET_KEY,
								],
							'region'=>self::AWS_REGION,
							]);
	}
	

	/*
		Terminate instance by InstanceId.
	*/
	public function terminateInstance($params=[]) {
		$ret = false;
		$required = ["InstanceId"];
		foreach ($required as $r) {
			if (!isset($params[$r])) return $ret;
		}
		
		try {
			$ret = $this->client->terminateInstances([
		 											'DryRun' => false,
	   	 											//'Force' => true,
	   	 											'InstanceIds' => [$params["InstanceId"]], // REQUIRED
	   											 ]);
			return $ret;
		} catch (Exception $e) {
			return $ret;
		}
	}

	/*
		Create instance by AMIImageId.
	*/
	public function createInstanceByImage($params=[]) {
	   	$ret = false;
		$required = ["ImageId", "KeyName", "SecurityGroup", "InstanceType"];
		foreach ($required as $r) {
			if (!isset($params[$r])) return $ret;
		}

		$options = [
	   		'ImageId' => $params["ImageId"],
	   		'MinCount' => $params["MinCount"] ?? 1,
	   		'MaxCount' => $params["MaxCount"] ?? 1,
			'KeyName' => $params["KeyName"],
	   		'SecurityGroups' => [
	   				$params["SecurityGroup"],
	   		],
	   		'InstanceType' => $params["InstanceType"],
	   	];
		$result = $this->client->RunInstances($options);
		return $result;
	}

	
	/*
		Getters for my own instance info.
	*/
	public function getMyInstanceMetaDataKeys() {
		return file_get_contents(self::AWS_META_ENDPOINT);		
	}

	public function getMyInstanceMetaData($key) {
		return file_get_contents(self::AWS_META_ENDPOINT .$key);		
	}

	public function getMyGlobalIP() {
		return file_get_contents(self::AWS_META_ENDPOINT ."public-ipv4");		
	}

	public function getMyLocalIP() {
		return file_get_contents(self::AWS_META_ENDPOINT ."local-ipv4");		
	}

	public function getMyPublicHostName() {
		return file_get_contents(self::AWS_META_ENDPOINT ."public-hostname");		
	}

	public function getMyInstanceId() {
		return file_get_contents(self::AWS_META_ENDPOINT ."instance-id");		
	}

	public function getMyAMIId() {
		return file_get_contents(self::AWS_META_ENDPOINT ."ami-id");		
	}

	public function getMyInstanceType() {
		return file_get_contents(self::AWS_META_ENDPOINT ."instance-type");		
	}

	public function getMySecurityGroups() {
		return file_get_contents(self::AWS_META_ENDPOINT ."security-groups");		
	}
	
}


